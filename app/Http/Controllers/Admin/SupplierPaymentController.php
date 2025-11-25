<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCrudPermissions;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SupplierPaymentController extends Controller
{
    use HasCrudPermissions;

    public function __construct()
    {
        $this->applyCrudPermissions('supplier_payments');
    }
    public function index(Request $request)
    {
        // إذا تم إرسال supplier_id
        if ($request->supplier_id) {

            $supplier = Supplier::findOrFail($request->supplier_id);

            $query = SupplierPayment::where('supplier_id', $request->supplier_id);
        } else {

            // بدون فلترة عميل → عرض الكل
            $supplier = null;
            $query = SupplierPayment::query();
        }

        // تطبيق الفلاتر
        $payments = $query
            ->when($request->search, function ($q) use ($request) {
                $q->where('note', 'LIKE', '%' . $request->search . '%');
            })
            ->when($request->type, function ($q) use ($request) {
                $q->where('type', $request->type);
            })
            ->when($request->date_from, function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->with('creator')
            ->latest()
            ->paginate(20)
            ->appends($request->query());

        // totals (لو فيه supplier_id فقط)
        $totalPay = $request->supplier_id
            ? SupplierPayment::where('supplier_id', $request->supplier_id)->where('type', 'pay')->sum('amount')
            : SupplierPayment::where('type', 'pay')->sum('amount');

        $totalReceive = $request->supplier_id
            ? SupplierPayment::where('supplier_id', $request->supplier_id)->where('type', 'receive')->sum('amount')
            : SupplierPayment::where('type', 'receive')->sum('amount');

        $netBalance = $request->supplier_id
            ? $totalReceive - $totalPay
            : $totalReceive - $totalPay;

        return view('admin.supplier_payment.index', [
            'payments'     => $payments,
            'supplier'     => $supplier,
            'search'       => $request->search,
            'type'         => $request->type,
            'totalPay'     => $totalPay,
            'totalReceive' => $totalReceive,
            'netBalance'   => $netBalance,
        ]);
    }
    /**
     * Show the form for creating a new payment.
     */
    public function create(Request $request)
    {
        $suppliers =  Supplier::all('id', 'name');
        $supplier =  Supplier::where('id', $request->supplier_id)->first(['id', 'total']) ?? null;
        return view('admin.supplier_payment.create', compact('suppliers', 'supplier'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'type'        => ['required', Rule::in(['pay', 'receive'])],
            'amount'      => 'required|numeric|min:0.01',
            'note'        => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $supplier = Supplier::findOrFail($request->supplier_id);

            // 1. إنشاء سجل الدفع
            $payment = SupplierPayment::create([
                'supplier_id' => $request->supplier_id,
                'type'        => $request->type,
                'amount'      => $request->amount,
                'note'        => $request->note,
                'created_by'  => auth()->id(),
            ]);

            // 2. تحديث رصيد العميل
            // افتراض: حقل 'total' في جدول 'suppliers' يمثل رصيد العميل.
            // إذا كان 'receive' (العميل يدفع لك)، يتم تقليل رصيده (المديونية أو زياده في الرصيد الدائن).
            // إذا كان 'pay' (أنت تدفع للعميل)، يتم زيادة رصيده (المديونية أو نقص في الرصيد الدائن).
            // يتم استخدام 'total' هنا لتمثيل صافي المعاملات مع العميل.

            if ($request->type === 'receive') {
                // العميل يدفع لك، يقلل من دينه أو يزيد من رصيده الدائن لديك.
                // يتم طرح المبلغ من حقل 'total' (لتقليل المديونية)
                $supplier->increment('total', $request->amount);
            } else { // 'pay'
                // أنت تدفع للعميل، يزيد من دينه أو يقلل من رصيده الدائن لديك.
                // يتم إضافة المبلغ إلى حقل 'price' (لزيادة المديونية)
                $supplier->decrement('total', $request->amount);
            }

            DB::commit();

            return redirect()->route('supplier_payments.index', [
                'supplier_id' => $request->supplier_id
            ])->with('success', __('admin.Payment recorded successfully'));
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء حفظ الدفعة: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified payment. 
     */
    public function edit(SupplierPayment $supplier_payment)
    {
        $supplier = Supplier::findOrFail($supplier_payment->supplier_id);
        return view('admin.supplier_payment.edit', compact('supplier_payment', 'supplier'));
    }


    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, SupplierPayment $supplierpayment)
    {
        $request->validate([
            'type'   => ['required', Rule::in(['pay', 'receive'])],
            'amount' => 'required|numeric|min:0.01',
            'note'   => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $supplier = Supplier::findOrFail($supplierpayment->supplier_id);
            $oldAmount = $supplierpayment->amount;
            $oldType = $supplierpayment->type;
            $newAmount = $request->amount;
            $newType = $request->type;

            // 1. عكس تأثير القيد القديم على رصيد العميل
            if ($oldType === 'receive') {
                $supplier->decrement('total', $oldAmount); // طرح المبلغ القديم من 'total'

            } else { // 'pay'
                $supplier->increment('total', $oldAmount); // إضافة المبلغ القديم لـ 'total'

            }
            $supplier->save();

            // 2. تحديث سجل الدفع
            $supplierpayment->update([
                'type'   => $newType,
                'amount' => $newAmount,
                'note'   => $request->note,
            ]);

            // 3. تطبيق تأثير القيد الجديد على رصيد العميل
            if ($newType === 'receive') {
                $supplier->decrement('price', $newAmount);
            } else { // 'pay'
                $supplier->increment('price', $newAmount);
            }
            $supplier->save();

            DB::commit();

            return redirect()->route('supplier_payments.index', [
                'supplier_id' => $supplierpayment->supplier_id
            ])->with('success', __('admin.Payment updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء تحديث الدفعة: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $paymentIds = explode(',', $request->id);

            foreach (SupplierPayment::whereIn('id', $paymentIds)->get() as $payment) {
                $supplier = Supplier::findOrFail($payment->supplier_id);




                // عكس تأثير الدفعة على رصيد العميل
                if ($payment->type === 'receive') {
                    // إلغاء عملية طرح سابقة
                    $supplier->decrement('total', $payment->amount);
                } else { // 'pay'

                    $supplier->increment('total', $payment->amount);
                    // إلغاء عملية إضافة سابقة
                }
                $supplier->save();

                $payment->delete();
            }

            DB::commit();
            session()->flash('success', __('admin.Deleted Successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }

        return redirect()->back();
    }
}
