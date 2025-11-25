<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCrudPermissions;
use App\Models\CustomerPayment; // يفترض وجود هذا النموذج
use App\Models\Customer; // يفترض وجود نموذج العميل
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // للاستخدام في التحقق من صحة البيانات

class CustomerPaymentController extends Controller
{
    use HasCrudPermissions;

    public function __construct()
    {
        $this->applyCrudPermissions('customer_payments');
    }
    /**
     * Display a listing of customer payments for a specific customer.
     * يعرض قائمة بمدفوعات العميل مع فلاتر البحث.
     */
    public function index(Request $request)
    {
        // إذا تم إرسال customer_id
        if ($request->customer_id) {

            $customer = Customer::findOrFail($request->customer_id);

            $query = CustomerPayment::where('customer_id', $request->customer_id);
        } else {

            // بدون فلترة عميل → عرض الكل
            $customer = null;
            $query = CustomerPayment::query();
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

        // totals (لو فيه customer_id فقط)
        $totalPay = $request->customer_id
            ? CustomerPayment::where('customer_id', $request->customer_id)->where('type', 'pay')->sum('amount')
            : CustomerPayment::where('type', 'pay')->sum('amount');

        $totalReceive = $request->customer_id
            ? CustomerPayment::where('customer_id', $request->customer_id)->where('type', 'receive')->sum('amount')
            : CustomerPayment::where('type', 'receive')->sum('amount');

        $netBalance = $request->customer_id
            ? $totalReceive - $totalPay
            : $totalReceive - $totalPay;

        return view('admin.customer_payment.index', [
            'payments'     => $payments,
            'customer'     => $customer,
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
        $customers =  Customer::all('id', 'name');
        $customer =  Customer::where('id', $request->customer_id)->first(['id', 'price']) ?? null;
        return view('admin.customer_payment.create', compact('customers', 'customer'));
    }
    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'type'        => ['required', Rule::in(['pay', 'receive'])],
            'amount'      => 'required|numeric|min:0.01',
            'note'        => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $customer = Customer::findOrFail($request->customer_id);

            // 1. إنشاء سجل الدفع
            $payment = CustomerPayment::create([
                'customer_id' => $request->customer_id,
                'type'        => $request->type,
                'amount'      => $request->amount,
                'note'        => $request->note,
                'created_by'  => auth()->id(),
            ]);

            // 2. تحديث رصيد العميل
            // افتراض: حقل 'total' في جدول 'customers' يمثل رصيد العميل.
            // إذا كان 'receive' (العميل يدفع لك)، يتم تقليل رصيده (المديونية أو زياده في الرصيد الدائن).
            // إذا كان 'pay' (أنت تدفع للعميل)، يتم زيادة رصيده (المديونية أو نقص في الرصيد الدائن).
            // يتم استخدام 'total' هنا لتمثيل صافي المعاملات مع العميل.

            if ($request->type === 'receive') {
                // العميل يدفع لك، يقلل من دينه أو يزيد من رصيده الدائن لديك.
                // يتم طرح المبلغ من حقل 'total' (لتقليل المديونية)
                $customer->decrement('price', $request->amount);
            } else { // 'pay'
                // أنت تدفع للعميل، يزيد من دينه أو يقلل من رصيده الدائن لديك.
                // يتم إضافة المبلغ إلى حقل 'price' (لزيادة المديونية)
                $customer->increment('price', $request->amount);
            }

            DB::commit();

            return redirect()->route('customer_payments.index', [
                'customer_id' => $request->customer_id
            ])->with('success', __('admin.Payment recorded successfully'));
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء حفظ الدفعة: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(CustomerPayment $customerPayment)
    {
        $customer = Customer::findOrFail($customerPayment->customer_id);
        return view('admin.customer_payment.edit', compact('customerPayment', 'customer'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, CustomerPayment $customerPayment)
    {
        $request->validate([
            'type'   => ['required', Rule::in(['pay', 'receive'])],
            'amount' => 'required|numeric|min:0.01',
            'note'   => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::findOrFail($customerPayment->customer_id);
            $oldAmount = $customerPayment->amount;
            $oldType = $customerPayment->type;
            $newAmount = $request->amount;
            $newType = $request->type;

            // 1. عكس تأثير القيد القديم على رصيد العميل
            if ($oldType === 'receive') {
                $customer->increment('price', $oldAmount); // إضافة المبلغ القديم لـ 'total'
            } else { // 'pay'
                $customer->decrement('price', $oldAmount); // طرح المبلغ القديم من 'total'
            }
            $customer->save();

            // 2. تحديث سجل الدفع
            $customerPayment->update([
                'type'   => $newType,
                'amount' => $newAmount,
                'note'   => $request->note,
            ]);

            // 3. تطبيق تأثير القيد الجديد على رصيد العميل
            if ($newType === 'receive') {
                $customer->decrement('price', $newAmount);
            } else { // 'pay'
                $customer->increment('price', $newAmount);
            }
            $customer->save();

            DB::commit();

            return redirect()->route('customer_payments.index', [
                'customer_id' => $customerPayment->customer_id
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

            foreach (CustomerPayment::whereIn('id', $paymentIds)->get() as $payment) {
                $customer = Customer::findOrFail($payment->customer_id);

                // عكس تأثير الدفعة على رصيد العميل
                if ($payment->type === 'receive') {
                    $customer->increment('price', $payment->amount); // إلغاء عملية طرح سابقة
                } else { // 'pay'
                    $customer->decrement('price', $payment->amount); // إلغاء عملية إضافة سابقة
                }
                $customer->save();

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
