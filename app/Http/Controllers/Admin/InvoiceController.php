<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCrudPermissions;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\CustomerPayment;
use App\Models\ProductItem;
use App\Models\ReturnItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Mpdf\Tag\P;

class InvoiceController extends Controller
{
    use HasCrudPermissions;

    public function __construct()
    {
        $this->applyCrudPermissions('invoices');
    }

    public function index(Request $request)
    {
        // الاستعلام الأساسي لعرض البيانات (سيتأثر بالـ paginate)
        $query = Invoice::query();

        // استعلام منفصل لحساب الإجماليات (لن يتأثر بالـ paginate)
        $sumQuery = Invoice::query();

        // استعلام لحساب إجمالي المرتجعات
        $returnQuery = ReturnItem::query();


        // -------------------------
        // 🔍 البحث بالـ search (id أو اسم العميل)
        // -------------------------
        if ($request->search) {
            $filter = function ($q) use ($request) {
                $q->where('id', $request->search)
                    ->orWhereHas('customer', function ($q2) use ($request) {
                        $q2->where('name', 'LIKE', '%' . $request->search . '%');
                    });
            };

            $query->where($filter);
            $sumQuery->where($filter);

            $returnQuery->whereHas('invoice.customer', function ($q3) use ($request) {
                $q3->where('name', 'LIKE', '%' . $request->search . '%');
            });
        }


        // -------------------------
        // 🔍 البحث بنوع الفاتورة
        // -------------------------
        if ($request->type) {
            $query->where('type', $request->type);
            $sumQuery->where('type', $request->type);

            $returnQuery->whereHas('invoice', function ($q4) use ($request) {
                $q4->where('type', $request->type);
            });
        }
        $total = Customer::sum('price');


        // -------------------------
        // 🔍 البحث بالـ customer_id (الإضافة الجديدة)
        // -------------------------
        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
            $sumQuery->where('customer_id', $request->customer_id);

            $returnQuery->whereHas('invoice', function ($q) use ($request) {
                $q->where('customer_id', $request->customer_id);
            });
           
             $total = Customer::FindOrFail($request->customer_id)->price; 
        }


        // -------------------------
        // 🔍 البحث بالتاريخ
        // -------------------------
        if ($request->from_date && $request->to_date) {
            $range = [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ];

            $query->whereBetween('created_at', $range);
            $sumQuery->whereBetween('created_at', $range);

            $returnQuery->whereBetween('created_at', $range);
        }


        // -------------------------
        // 📌 تنفيذ الاستعلام الأساسي مع pagination
        // -------------------------
        $invoice = $query->where('type', '!=', 3)->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->query());
 

        // -------------------------
        // 📌 حساب الإجماليات
        // -------------------------
        $grossTotal      = $sumQuery->where('type', '!=', 3)->sum('total');
        $grossDiscount   = $sumQuery->where('type', '!=', 3)->sum('discount');
        $grossPaid       = $request->customer_id ? $sumQuery->where('type', '!=', 3)->sum('paid') + CustomerPayment::where('customer_id', $request->customer_id)->sum('amount') : $sumQuery->where('type', '!=', 3)->sum('paid') + CustomerPayment::sum('amount');
        $grossRemaining  = $total ;
        $totalReturnAmount = $returnQuery->sum('total');


        // -------------------------
        // 📌 نتائج الإحصائيات
        // -------------------------
        $totalAmount   = $grossTotal;
        $monthlyAmount = $grossPaid;
        $todayAmount   = $grossRemaining;
        $yesterdayAmount = $grossDiscount;
        $totalReturn   = $totalReturnAmount;


        // -------------------------
        // 📌 إرجاع البيانات للواجهة
        // -------------------------
        return view('admin.invoice.index', compact(
            'invoice',
            'totalAmount',
            'monthlyAmount',
            'todayAmount',
            'yesterdayAmount',
            'totalReturn'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $branches  = Branch::all();
        $users  = User::all();



        // dd(memory_get_usage(true) / 1024 / 1024 . ' MB');

        return view('admin.invoice.create', compact('customers', 'branches', 'users'));
    }
    public function show(Request $request)
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
                'price' => $item->sell_price,
                'price2' => $item->sell_price2,
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
            'customer_id'       => 'required|exists:customers,id',
            'branch_id'         => 'required|exists:branches,id',
            'product_item_id'   => 'required|array',
            'qty'               => 'required|array',
            'price'             => 'required|array',
            'remaining'         => 'required|numeric|min:0',

            // افتراض حقول جديدة للمرتجعات
            'return_product_item_id' => 'nullable|array',
            'return_qty'             => 'nullable|array',
            'return_price'           => 'nullable|array',
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

        // 3. تصفية عناصر المرتجعات الصالحة
        $returnProductIds = [];
        $returnQtys = [];
        $returnPrices = [];
        $hasReturns = false;

        if ($request->filled('return_product_item_id')) {
            foreach ($request->return_product_item_id as $index => $productId) {
                $qty   = $request->return_qty[$index] ?? 0;
                $price = $request->return_price[$index] ?? 0;

                // شرط التحقق من المرتجع: يجب أن يكون المنتج موجودًا، الكمية > 0، والسعر >= 0
                if ($productId && $qty > 0 && $price >= 0) {
                    $returnProductIds[] = $productId;
                    $returnQtys[]       = $qty;
                    $returnPrices[]     = $price;
                    $hasReturns = true;
                }
            }
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

                if ($item->qty < $qtys[$index]) {
                    DB::rollBack();
                    return back()->withErrors([
                        'msg' => 'الكمية المطلوبة للمنتج ' . $item->name . ' غير متوفرة. المتاح: ' . $item->qty
                    ]);
                }
            }

            // 4.2. إنشاء الفاتورة
            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'branch_id'   => $request->branch_id,
                'user_id'     => $request->user_id, // تأكد أن هذا الحقل موجود
                'total'       => $request->total,   // تأكد أن هذا الحقل موجود ومحسوب
                'paid'        => $request->paid,    // تأكد أن هذا الحقل موجود
                'remaining'   => $request->remaining,
                'discount'    => $request->discount ?? 0,
                'note'        => $request->note,
                'type'        => $request->type ?? '1',
                'created_by'  => auth()->id(),
            ]);

            $incomeTotal = 0;
            // 4.3. حفظ عناصر الفاتورة + خصم المخزون
            foreach ($productIds as $index => $productId) {

                $item = ProductItem::find($productId);
                if ($request->type == 1) {
                    $income = $item->sell_price - $item->price;
                } else {
                    $income = $item->sell_price2 - $item->price;
                }
                $incomeTotal += $income;

                InvoiceItem::create([
                    'invoice_id'      => $invoice->id,
                    'product_item_id' => $productId,
                    'qty'             => $qtys[$index],
                    'price'           => $prices[$index],
                    'income'           => $qtys[$index] * $income,
                    'type'        => $request->type ?? '1',
                    'total'           => $qtys[$index] * $prices[$index],
                ]);

                // خصم الكمية من المخزون
                $item = ProductItem::find($productId);
                // استخدم التحديث التلقائي للحماية من السباق (Race Conditions)
                $item->decrement('qty', $qtys[$index]);
                // ملاحظة: يمكنك استخدام find($productId)->decrement('qty', $qtys[$index]) مباشرة.
            }
            $invoice->income = $incomeTotal;
            $invoice->save();
            // 4.4. ✅ حفظ المرتجعات وإضافة الكمية إلى المخزون (المنطق الإضافي)
            if ($hasReturns) {
                foreach ($returnProductIds as $index => $productId) {
                    $returnTotal = $returnQtys[$index] * $returnPrices[$index];

                    // إضافة المرتجع إلى جدول return_items
                    ReturnItem::create([
                        'customer_id' => $request->customer_id,
                        'invoice_id'      => $invoice->id,
                        'product_item_id' => $productId,
                        'qty'             => $returnQtys[$index],
                        'price'           => $returnPrices[$index],
                        'total'           => $returnTotal,
                        'created_by'      => auth()->id(), // افتراض وجود هذا الحقل
                    ]);

                    // زيادة الكمية في المخزون
                    $item = ProductItem::find($productId);
                    // زيادة المخزون باستخدام الدالة increment للحماية من السباق
                    $item->increment('qty', $returnQtys[$index]);
                }
            }

            // 4.5. تحديث رصيد العميل
            $customer = Customer::find($request->customer_id);
            $customer->increment('price', $request->remaining);

            DB::commit();

            // 5. إعادة التوجيه بناءً على خيار الحفظ
            if ($request->save == 'print') {
                return redirect()->route('invoice.print', $invoice->id)->with('success', __('admin.Created Successfully'));
            } elseif ($request->save == 'print2') {
                return redirect()->route('invoice.print2', $invoice->id)->with('success', __('admin.Created Successfully'));
            } else {
                return redirect()->route('invoice.index', ['customer_id' => $request->customer_id])
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



    public function print2($id)
    {

        $invoice = Invoice::with('items.productItem.product')->findOrFail($id);
        return view('admin.invoice.print2', compact('invoice'));
    }

    public function print3($id)
    {

        $invoice = Invoice::with('items.productItem.product')->findOrFail($id);
        return view('admin.invoice.print3', compact('invoice'));
    }


    public function print($id)
    {

        $invoice = Invoice::with('items.productItem.product')->findOrFail($id);
        return view('admin.invoice.print', compact('invoice'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load(['items.productItem']);

        $customers = Customer::all();
        $branches  = Branch::all();
        $users  = User::all();
        return view('admin.invoice.edit', compact(
            'invoice',
            'customers',
            'branches',
            'users',
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'user_id' => 'required|exists:users,id',
            'total' => 'required|numeric',
            'paid' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {

            $Customer = Customer::find($invoice->customer_id);

            // ✅ رجّع المتبقي القديم للعميل
            $Customer->price -= $invoice->remaining;
            $Customer->save();

            /*
        |--------------------------------------------------------------------------
        | ✅ 1) رجّع المخزون القديم قبل التعديل
        |--------------------------------------------------------------------------
        */
            foreach ($invoice->items as $oldItem) {
                $product = ProductItem::find($oldItem->product_item_id);
                if ($product) {
                    $product->qty += $oldItem->qty; // إرجاع الكمية القديمة
                    $product->save();
                }
            }

            /*
        |--------------------------------------------------------------------------
        | ✅ 2) تحقق من المخزون الجديد قبل حفظ الفاتورة
        |--------------------------------------------------------------------------
        */
            $submitted = $request->product_item_id ?? [];

            foreach ($submitted as $key => $productId) {

                if (!$productId) continue;

                $qty = $request->qty[$key] ?? 0;

                $product = ProductItem::find($productId);

                if ($product->qty < $qty) {
                    DB::rollBack();
                    return back()->withErrors([
                        'msg' => "الكمية المطلوبة للمنتج {$product->name} غير متوفرة. المتاح: {$product->qty}"
                    ]);
                }
            }

            /*
        |--------------------------------------------------------------------------
        | ✅ 3) تحديث الفاتورة الأساسية
        |--------------------------------------------------------------------------
        */
            $invoice->update([
                'branch_id' => $request->branch_id,
                'user_id' => $request->user_id,
                'total' => $request->total,
                'discount' => $request->discount,
                'net' => $request->net,
                'paid' => $request->paid,
                'remaining' => $request->remaining,
                'note' => $request->note,
            ]);

            /*
        |--------------------------------------------------------------------------
        | ✅ 4) حفظ الأصناف الجديدة + تحديث الموجودة + حذف المحذوفة
        |--------------------------------------------------------------------------
        */

            $existingItems = $invoice->items()->pluck('id', 'product_item_id')->toArray();
            $processed = [];
            $incomeTotal = 0;
            foreach ($submitted as $key => $productId) {

                if (!$productId) continue;

                $qty = $request->qty[$key] ?? 1;
                $price = $request->price[$key] ?? 0;
                $total = $qty * $price;

                $item = ProductItem::find($productId);
                if ($invoice->type == 1) {
                    $income = $item->sell_price - $item->price;
                } else {
                    $income = $item->sell_price2 - $item->price;
                }
                $incomeTotal +=  $qty * $income;
                if (isset($existingItems[$productId])) {

                    // ✅ تحديث
                    $invoice->items()->where('id', $existingItems[$productId])->update([
                        'qty' => $qty,
                        'price' => $price,
                        'total' => $total,
                        'type' => $invoice->type,
                        'income' => $qty * $income,
                    ]);

                    $processed[] = $existingItems[$productId];
                } else {

                    $item = ProductItem::find($productId);
                    if ($invoice->type == 1) {
                        $income = $item->sell_price - $item->price;
                    } else {
                        $income = $item->sell_price2 - $item->price;
                    }
                    $incomeTotal += $qty * $income;
                    // ✅ إضافة جديد
                    $newItem = $invoice->items()->create([
                        'product_item_id' => $productId,
                        'qty' => $qty,
                        'price' => $price,
                        'total' => $total,
                        'type' => $invoice->type,
                        'income' => $qty * $income,
                    ]);
                    $processed[] =   $newItem->id;
                }

                /*
            |--------------------------------------------------------------------------
            | ✅ 5) خصم الكمية الجديدة من المخزون
            |--------------------------------------------------------------------------
            */
                $product = ProductItem::find($productId);
                $product->qty -= $qty;
                $product->save();
            }
            $invoice->income = $incomeTotal;
            $invoice->save();
            // ✅ حذف العناصر اللي اتشالت من الفورم
            $invoice->items()->whereNotIn('id', $processed)->delete();

            /*
        |--------------------------------------------------------------------------
        | ✅ 6) تحديث رصيد العميل بالمتبقي الجديد
        |--------------------------------------------------------------------------
        */
            $Customer->price += $request->remaining;
            $Customer->save();

            DB::commit();

            if ($request->save == 'print') {
                return redirect()->route('invoice.print', $invoice->id);
            } elseif ($request->save == 'print2') {
                return redirect()->route('invoice.print', $invoice->id);
            }

            return redirect()->route('invoice.index', ['customer_id' => $request->customer_id])
                ->with('success', __('admin.Updated Successfully'));
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
        $ids = explode(',', $request->id);

        Invoice::whereIn('id', $ids)->each(function ($invoice) {
            $invoice->items()->delete();
            $invoice->delete();
        });

        return back()->with('success', __('admin.Deleted Successfully'));
    }
}
