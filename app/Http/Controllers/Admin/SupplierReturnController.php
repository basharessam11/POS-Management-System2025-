<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCrudPermissions;
use App\Models\ProductItem;
use App\Models\ReturnItem;
use App\Models\Supplier;
use App\Models\SupplierInvoice;
use App\Models\SupplierPayment;
use App\Models\SupplierReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierReturnController extends Controller
{
    use HasCrudPermissions;

    public function __construct()
    {
        $this->applyCrudPermissions('supplier_returns');
    }
    public function index(Request $request)
    {
        $supplierId = $request->supplier_id ?? $request->id; // <-- هنا

        $query = SupplierInvoice::query();
        $sumQuery = SupplierInvoice::query();
        $returnQuery = SupplierReturn::query();

        // -------------------------
        // 🔍 search بالاسم أو ID
        // -------------------------
        if ($request->search) {
            $filter = function ($q) use ($request) {
                $q->where('id', $request->search)
                    ->orWhereHas('supplier', function ($q2) use ($request) {
                        $q2->where('name', 'LIKE', '%' . $request->search . '%');
                    });
            };

            $query->where($filter);
            $sumQuery->where($filter);
            $returnQuery->whereHas('supplierInvoice.supplier', function ($q3) use ($request) {
                $q3->where('name', 'LIKE', '%' . $request->search . '%');
            });
        }

        // -------------------------
        // 🔍 الفلترة حسب نوع الفاتورة
        // -------------------------
        if ($request->type) {
            $query->where('type', $request->type);
            $sumQuery->where('type', $request->type);
            $returnQuery->whereHas('supplierInvoice', function ($q4) use ($request) {
                $q4->where('type', $request->type);
            });
        }

        // -------------------------
        // 🔍 الفلترة بـ supplier_id أو id
        // -------------------------
        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
            $sumQuery->where('supplier_id', $supplierId);

            $returnQuery->whereHas('supplierInvoice', function ($q) use ($supplierId) {
                $q->where('supplier_id', $supplierId);
            });

            $grossRemaining = Supplier::where('id', $supplierId)->sum('total');
            $payments = SupplierPayment::where('supplier_id', $supplierId)->sum('amount');
        } else {
            $grossRemaining = Supplier::sum('total');
            $payments = SupplierPayment::sum('amount');
        }

        // -------------------------
        // 🔍 فلترة التاريخ
        // -------------------------
        if ($request->from_date && $request->to_date) {
            $range = [
                $request->from_date . ' 00:00:00',
                $request->to_date   . ' 23:59:59'
            ];

            $query->whereBetween('created_at', $range);
            $sumQuery->whereBetween('created_at', $range);
            $returnQuery->whereBetween('created_at', $range);
        }

        // -------------------------
        // 📌 تنفيذ الاستعلام الأساسي
        // -------------------------
        $invoice = $query->where('type', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->query());

        // -------------------------
        // 📌 حساب الإجماليات
        // -------------------------





        $totalAmount = $supplierId
            ? SupplierInvoice::where('type', '=', 0)->where('supplier_id', $supplierId)->sum('total') : SupplierInvoice::where('type', '=', 0)->sum('total');
        $totalReturn = $supplierId
            ? SupplierInvoice::where('type', '=', 0)->where('supplier_id', $supplierId)->sum('total') : SupplierInvoice::where('type', '=', 0)->sum('total');
        $monthlyAmount = $supplierId
            ? SupplierInvoice::where('type', '=', 0)->where('supplier_id', $supplierId)->sum('paid') + SupplierPayment::where('supplier_id', $supplierId)->sum('amount') : SupplierInvoice::where('type', '=', 0)->sum('paid') + SupplierPayment::sum('amount');
        $todayAmount = Supplier::when($supplierId, fn($q) => $q->where('id', $supplierId))
            ->sum('total');

        return view('admin.supplier_returns.index', compact(
            'invoice',
            'totalAmount',
            'monthlyAmount',
            'todayAmount',
            'totalReturn'
        ));
    }
    public function create(Request $request)
    {
        $suppliers = Supplier::get(['name', 'id', 'total']);


        // dd(memory_get_usage(true) / 1024 / 1024 . ' MB');

        return view('admin.supplier_returns.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',


            'note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            // إنشاء فاتورة المرتجع
            $invoice = SupplierInvoice::create([
                'supplier_id' => $request->supplier_id,

                'number'   => $request->supplier_id,
                'date'     => $request->date,
                'type'     => 1,
                'total'       => $request->net, // سيتم تحديثه بعد جمع الإجماليات
                'paid'        => $request->paid,
                'remaining'   => $request->net - $request->paid,
                'created_by'  => auth()->id(),
            ]);

            $totalInvoice = 0;

            foreach ($request->return_product_item_id as $key => $productItemId) {
                $qty = $request->return_qty[$key] ?? 0;
                $price = $request->return_price[$key] ?? 0;

                if (!$productItemId || $qty <= 0 || $price <= 0) {
                    continue;
                }

                $itemTotal = $qty * $price;
                $totalInvoice += $itemTotal;

                // إنشاء المرتجعات
                SupplierReturn::create([
                    'supplier_invoice_id' => $invoice->id,
                    'supplier_id'         => $request->supplier_id,
                    'product_item_id'     => $productItemId,
                    'qty'                 => $qty,
                    'price'               => $price,
                    'total'               => $itemTotal,
                    'created_by'          => auth()->id(),
                ]);

                // خصم الكمية من المنتج
                $product = ProductItem::find($productItemId);
                if ($product) {
                    $product->qty = max(0, $product->qty - $qty);
                    $product->save();
                }
            }

            // تحديث إجمالي الفاتورة والرصيد المتبقي
            $invoice->total =  $request->net;
            $invoice->remaining = max(0,  $request->net - $request->paid);
            $invoice->save();

            // تحديث حساب المورد (إضافة المبلغ للرصيد)
            $supplier = Supplier::findOrFail($request->supplier_id);
            $supplier->total -= $totalInvoice;
            $supplier->save();
        });

        return redirect()->route('supplier_returns.index', ['id' => $request->supplier_id])
            ->with('success', __('admin.Created Successfully'));
    }



    public function show(SupplierReturn $returnItem)
    {
        return view('admin.supplier_returns.show', compact('returnItem'));
    }


    public function edit($invoice)
    {
        // return $invoice->returnItems;
        $invoice = SupplierInvoice::FindOrFail($invoice);

        $supplier = Supplier::where('id', $invoice->supplier_id)->first();

        return view('admin.supplier_returns.edit', compact(
            'invoice',
            'supplier',


        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',

            'paid' => 'required|numeric ',
            'note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $id) {

            $invoice = SupplierInvoice::findOrFail($id);
            $supplier = Supplier::findOrFail($request->supplier_id);
            // 1️⃣ إعادة العميل للوضع السابق قبل تعديل الفاتورة
            $supplier->total += abs($invoice->total);
            $supplier->save();

            // جلب المرتجعات القديمة
            $existingReturns = $invoice->returns()->pluck('id', 'product_item_id')->toArray();

            $newProductIds = $request->return_product_item_id ?? [];

            // حذف المرتجعات التي لم تعد موجودة في الطلب الجديد واسترجاع الكميات
            foreach ($existingReturns as $productId => $returnId) {
                if (!in_array($productId, $newProductIds)) {
                    $return = SupplierReturn::find($returnId);
                    if ($return) {
                        $product = ProductItem::find($return->product_item_id);
                        if ($product) {
                            $product->qty += $return->qty;
                            $product->save();
                        }
                        $return->delete();
                    }
                }
            }

            $totalInvoice = 0;

            foreach ($newProductIds as $key => $productItemId) {
                $qty = $request->return_qty[$key] ?? 0;
                $price = $request->return_price[$key] ?? 0;

                if (!$productItemId || $qty <= 0 || $price <= 0) continue;

                $itemTotal = $qty * $price;
                $totalInvoice += $itemTotal;

                if (isset($existingReturns[$productItemId])) {
                    // تحديث المرتجعات الموجودة
                    $return = SupplierReturn::find($existingReturns[$productItemId]);
                    $oldQty = $return->qty;
                    $return->qty = $qty;
                    $return->price = $price;
                    $return->total = $itemTotal;
                    $return->save();

                    // تعديل كمية المنتج
                    $product = ProductItem::find($productItemId);
                    if ($product) {
                        $product->qty = max(0, $product->qty + $oldQty - $qty);
                        $product->save();
                    }
                } else {
                    // إنشاء المرتجعات الجديدة
                    SupplierReturn::create([
                        'supplier_invoice_id' => $invoice->id,
                        'supplier_id'         => $request->supplier_id,
                        'product_item_id'     => $productItemId,
                        'qty'                 => $qty,
                        'price'               => $price,
                        'total'               => $itemTotal,
                        'created_by'          => auth()->id(),
                    ]);

                    $product = ProductItem::find($productItemId);
                    if ($product) {
                        $product->qty = max(0, $product->qty - $qty);
                        $product->save();
                    }
                }
            }

            // تحديث إجمالي الفاتورة والرصيد المتبقي
            $invoice->total = $request->net;
            $invoice->paid = $request->paid;
            $invoice->remaining = max(0, $request->net - $request->paid);

            $invoice->save();

            // تحديث حساب المورد

            $supplier->total -= $totalInvoice;
            $supplier->save();
        });

        return redirect()->route('supplier_returns.index', ['supplier_id' => $request->supplier_id])
            ->with('success',  __('admin.Updated Successfully'));
    }




    public function print2($id)
    {

        $invoice = SupplierInvoice::with('items.productItem.product')->findOrFail($id);
        return view('admin.supplier_returns.print2', compact('invoice'));
    }

    public function print3($id)
    {

        $invoice = SupplierInvoice::with('items.productItem.product')->findOrFail($id);
        return view('admin.supplier_returns.print3', compact('invoice'));
    }


    public function print($id)
    {

        $invoice = SupplierInvoice::with('items.productItem.product')->findOrFail($id);
        return view('admin.supplier_returns.print', compact('invoice'));
    }




    public function destroy(Request $request)
    {
        $ids = explode(',', $request->id);

        DB::transaction(function () use ($ids) {
            $invoices = SupplierInvoice::whereIn('id', $ids)->get();

            foreach ($invoices as $invoice) {
                // إعادة الرصيد للعميل
                $supplier = $invoice->supplier;
                if ($supplier) {
                    $supplier->total += abs($invoice->total);
                    $supplier->save();
                }

                // إعادة المخزون لجميع عناصر المرتجع
                foreach ($invoice->returnItems as $item) {
                    $product = $item->productItem;
                    if ($product) {
                        $product->qty += $item->qty;
                        $product->save();
                    }

                    // حذف عنصر المرتجع (Soft Delete)
                    $item->delete();
                }

                // حذف الفاتورة نفسها (Soft Delete)
                $invoice->delete();
            }
        });

        return redirect()->back()->with('success', __('admin.Deleted Successfully'));
    }
}
