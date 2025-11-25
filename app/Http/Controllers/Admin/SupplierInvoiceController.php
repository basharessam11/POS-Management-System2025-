<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCrudPermissions;
use App\Models\Supplier;
use App\Models\ProductItem;
use App\Models\ReturnItem;
use App\Models\SupplierInvoice;
use App\Models\SupplierInvoiceItem;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SupplierInvoiceController extends Controller
{
    use HasCrudPermissions;

    public function __construct()
    {
        $this->applyCrudPermissions('supplier_invoice');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = $request->id;

        $invoices = SupplierInvoice::where('type', 0)
            // فلترة حسب المورد إذا تم إرسال id
            ->when($id, function ($q) use ($id) {
                $q->where('supplier_id', $id);
            })

            // تاريخ من
            ->when($request->date_from, function ($q) use ($request) {
                $q->whereDate('date', '>=', $request->date_from);
            })
            // تاريخ إلى
            ->when($request->date_to, function ($q) use ($request) {
                $q->whereDate('date', '<=', $request->date_to);
            })
            ->latest()
            ->paginate(20)
            ->appends($request->query());


        $totalAmount = $id
            ? SupplierInvoice::where('type', '=', 0)->where('supplier_id', $id)->sum('total') : SupplierInvoice::where('type', '=', 0)->sum('total');
        $totalReturn = $id
            ? SupplierInvoice::where('type', '=', 0)->where('supplier_id', $id)->sum('total') : SupplierInvoice::where('type', '=', 0)->sum('total');
        $monthlyAmount = $id
            ? SupplierInvoice::where('type', '=', 0)->where('supplier_id', $id)->sum('paid') + SupplierPayment::where('supplier_id', $id)->sum('amount') : SupplierInvoice::where('type', '=', 0)->sum('paid') + SupplierPayment::sum('amount');
        $todayAmount = Supplier::when($id, fn($q) => $q->where('id', $id))
            ->sum('total');




        return view('admin.supplier_invoice.index', [
            'invoices' => $invoices,
            'search'   => $request->search,
            'id'       => $id,
            'totalAmount'       => $totalAmount,
            'totalReturn'       => $totalReturn,
            'monthlyAmount'       => $monthlyAmount,
            'todayAmount'       => $todayAmount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */


    public function print2($id)
    {

        $invoice = SupplierInvoice::with('items.productItem.product')->findOrFail($id);
        return view('admin.supplier_invoice.print2', compact('invoice'));
    }

    public function print3($id)
    {

        $invoice = SupplierInvoice::with('items.productItem.product')->findOrFail($id);
        return view('admin.supplier_invoice.print3', compact('invoice'));
    }

    public function create(Request $request)
    {
        $suppliers = Supplier::all();
        $productItems = ProductItem::all();
        return view('admin.supplier_invoice.create', compact('suppliers', 'productItems'));
    }


    public function show1(Request $request)
    {


        $q = $request->get('q', '');

        $items = ProductItem::with('product', 'product.brand')
            ->where(function ($query) use ($q) {
                $query->whereHas('product', function ($q2) use ($q) {
                    $q2->where('name', 'like', "%{$q}%");
                })
                    ->orwhereHas('product.brand', function ($q3) use ($q) {
                        $q3->where('name', 'like', "%{$q}%");
                    })
                ;
                // البحث بالباركود مباشرة
            })->orWhere('barcode', $q)->orWhere('id', $q)
            ->limit(20)
            ->get();


        $results = [];

        foreach ($items as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->product->name . ' — ' . $item->size . ' — ' . optional($item->product->brand)->name,
                'price' => $item->price,
                'price2' => $item->price,
                'stock' => $item->qty,
                'barcode' => $item->barcode,
            ];
        }

        return response()->json(['results' => $results]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ⚠️ تم إزالة السطر: return $request;

        // 1. التحقق من صحة البيانات (Validation)
        $request->validate([
            'date' => 'required|date',
            'product_item_id'   => 'required|array',
            'qty'               => 'required|array',
            'price'             => 'required|array',
            'supplier_id'         => 'required|exists:suppliers,id',
        ]);

        // 2. تصفية عناصر الفاتورة الصالحة
        $productIds = [];
        $qtys = [];
        $prices = [];

        foreach ($request->product_item_id as $index => $productId) {
            $qty   = $request->qty[$index] ?? 0;
            $price = $request->price[$index] ?? 0;

            if ($productId && $qty > 0 && $price >= 0) {
                $productIds[] = $productId;
                $qtys[]       = $qty;
                $prices[]     = $price;
            }
        }

        if (empty($productIds)) {
            return back()->withErrors(['product_item_id' => 'لا يمكن إنشاء فاتورة بدون منتجات']);
        }


        // 4. بدء المعاملة (Transaction)
        DB::beginTransaction();

        try {

            // 4.1. التحقق من مخزون **عناصر الفاتورة**
            foreach ($productIds as $index => $productId) {
                $item = ProductItem::find($productId);

                if (!$item) {
                    DB::rollBack();
                    return back()->withErrors(['msg' => 'المنتج غير موجود']);
                }
            }

            // 4.2. إنشاء الفاتورة
            $invoice = SupplierInvoice::create([
                'supplier_id'   => $request->supplier_id,
                'date'     => $request->date,
                'total'       => $request->total,
                'paid'       => $request->paid,
                'remaining'       => $request->remaining,
                'created_by'  => auth()->id(),
            ]);


            // 4.3. حفظ عناصر الفاتورة + خصم المخزون
            foreach ($productIds as $index => $productId) {

                SupplierInvoiceItem::create([
                    'invoice_id'      => $invoice->id,
                    'supplier_id'   => $request->supplier_id,
                    'product_item_id' => $productId,
                    'qty'             => $qtys[$index],
                    'price'           => $prices[$index],
                    'total'           => $qtys[$index] * $prices[$index],
                ]);
            }


            // 4.5. تحديث رصيد العميل
            $supplier = Supplier::find($request->supplier_id);
            $supplier->increment('total', $request->remaining);

            DB::commit();

            // 5. إعادة التوجيه بناءً على خيار الحفظ
            if ($request->save == 'print') {
                return redirect()->route('supplier_invoice.print2', $invoice->id);
            } elseif ($request->save == 'print2') {
                return redirect()->route('supplier_invoice.print3', $invoice->id);
            } else {
                return redirect()->route('supplier_invoice.index', [
                    'id' => $request->supplier_id
                ])
                    ->with('success', __('admin.Created Successfully'));
            }
        } catch (\Throwable $e) {
            DB::rollBack();

            // في بيئة الإنتاج، قد لا ترغب بعرض رسالة الخطأ للمستخدم
            return back()->withErrors([
                'msg' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()
            ]);
        }
    }


    /**
     * Display the specified resource.
     */

    public function show(SupplierInvoice $supplierInvoice)
    {




        return view('admin.supplier_invoice.show', compact('supplierInvoice'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupplierInvoice $supplierInvoice)
    {



        return view('admin.supplier_invoice.edit', compact('supplierInvoice'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupplierInvoice $supplierInvoice)
    {
        $request->validate([
            'date' => 'required|date',
            'product_item_id'   => 'required|array',
            'qty'               => 'required|array',
            'price'             => 'required|array',
            'supplier_id'         => 'required|exists:suppliers,id',
        ]);

        DB::beginTransaction();

        try {

            $supplier = Supplier::find($supplierInvoice->supplier_id);

            // ✅ رجّع المتبقي القديم للعميل
            $supplier->total -= $supplierInvoice->remaining;
            $supplier->save();



            $supplierInvoice->update([
                'date'     => $request->date,
                'total'       => $request->total,
                'paid'       => $request->paid,
                'remaining'       => $request->remaining,
                'created_by'  => auth()->id(),
            ]);



            $submitted = $request->product_item_id ?? [];
            $existingItems = $supplierInvoice->items()->pluck('id', 'product_item_id')->toArray();
            $processed = [];

            foreach ($submitted as $key => $productId) {

                if (!$productId) continue;

                $qty = $request->qty[$key] ?? 1;
                $price = $request->price[$key] ?? 0;
                $total = $qty * $price;


                if (isset($existingItems[$productId])) {

                    // ✅ تحديث
                    $supplierInvoice->items()->where('id', $existingItems[$productId])->update([
                        'invoice_id'      => $supplierInvoice->id,
                        'product_item_id' => $productId,
                        'qty'             => $qty,
                        'price'           => $price,
                        'total'           => $total


                    ]);

                    $processed[] = $existingItems[$productId];
                } else {
                    $total = $qty * $price;

                    $newItem = $supplierInvoice->items()->create([
                        'product_item_id' => $productId,
                        'qty'             => $qty,
                        'price'           => $price,
                        'total'           => $total,
                    ]);
                    $processed[] =   $newItem->id;
                }
            }
            // return  $processed;

            $supplierInvoice->items()->whereNotIn('id', $processed)->delete();

            /*
        |--------------------------------------------------------------------------
        | ✅ 6) تحديث رصيد العميل بالمتبقي الجديد
        |--------------------------------------------------------------------------
        */
            $supplier->total += $request->remaining;
            $supplier->save();

            DB::commit();
            Artisan::call('db:backup');
            if ($request->save == 'print2') {
                return redirect()->route('supplier_invoice.print2', $supplierInvoice->id);
            } elseif ($request->save == 'print3') {
                return redirect()->route('supplier_invoice.print3', $supplierInvoice->id);
            }

            return redirect()->route('supplier_invoice.index', [
                'id' => $supplierInvoice->supplier_id
            ])->with('success', __('admin.Updated Successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {


        $ex = explode(',', $request->id);

        SupplierInvoice::destroy($ex);

        session()->flash('success', __('admin.Deleted Successfully'));

        return redirect()->back();
    }
}
