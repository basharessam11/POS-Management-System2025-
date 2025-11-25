<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCrudPermissions;
use App\Models\Branch;
use App\Models\supplier;
use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\Invoice;
use App\Models\ProductItem;
use App\Models\ReturnItem;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnItemController extends Controller
{

    use HasCrudPermissions;

    public function __construct()
    {
        $this->applyCrudPermissions('returns');
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


        // -------------------------
        // 🔍 البحث بالـ customer_id (الإضافة الجديدة)
        // -------------------------
        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
            $sumQuery->where('customer_id', $request->customer_id);

            $returnQuery->whereHas('invoice', function ($q) use ($request) {
                $q->where('customer_id', $request->customer_id);
            });
            $grossRemaining  = Customer::where('id', $request->customer_id)->sum('price');
        } else {
            $grossRemaining  = Customer::sum('price');
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
        $invoice = $query->where('type', '=', 3)->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->query());


        // -------------------------
        // 📌 حساب الإجماليات
        // -------------------------
        $grossTotal      = $sumQuery->where('type', '!=', 3)->sum('total');
        $grossDiscount   = $sumQuery->where('type', '!=', 3)->sum('discount');
        $grossPaid       = $request->customer_id ? $sumQuery->where('type', '!=', 3)->sum('paid') + CustomerPayment::where('customer_id', $request->customer_id)->sum('amount') : $sumQuery->where('type', '!=', 3)->sum('paid') + CustomerPayment::sum('amount');
    
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
        return view('admin.returns.index', compact(
            'invoice',
            'totalAmount',
            'monthlyAmount',
            'todayAmount',
            'yesterdayAmount',
            'totalReturn'
        ));
    }

    public function create()
    {
        $customers = Customer::all();
        $branches  = Branch::all();
        $users  = User::all();



        // dd(memory_get_usage(true) / 1024 / 1024 . ' MB');

        return view('admin.returns.create', compact('customers', 'branches', 'users'));
    }

    public function store(Request $request)
    {


        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'branch_id'   => 'required|exists:branches,id',

            'return_qty.*' => 'required|numeric|min:1',
            'return_price.*' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            // إنشاء فاتورة المرتجع
            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'branch_id'   => $request->branch_id,
                'user_id'     => $request->user_id,
                'total'       => $request->net, // سيتم تحديثه بعد جمع الإجماليات
                'paid'        => $request->paid,
                'remaining'   => $request->net - $request->paid,
                'discount'    => 0,
                'note'        => $request->note,
                'type'        => 3, // نوع الفاتورة: مرتجع
                'created_by'  => auth()->id(),
            ]);



            foreach ($request->return_product_item_id as $key => $productItemId) {
                $qty = $request->return_qty[$key] ?? 0;
                $price = $request->return_price[$key] ?? 0;

                if (!$productItemId || $qty <= 0 || $price <= 0) {
                    continue;
                }

                $itemTotal = $qty * $price;


                // إنشاء المرتجعات
                ReturnItem::create([
                    'invoice_id'      => $invoice->id,
                    'customer_id'     => $request->customer_id,
                    'product_item_id' => $productItemId,
                    'qty'             => $qty,
                    'price'           => $price,
                    'total'           => $itemTotal,

                ]);

                // خصم الكمية من المنتج
                $product = ProductItem::find($productItemId);
                if ($product) {
                    $product->qty = max(0, $product->qty - $qty);
                    $product->save();
                }
            }
            $customer = Customer::findOrFail($request->customer_id);

            // 1️⃣ إعادة العميل للوضع السابق قبل تعديل الفاتورة
            $customer->price += $invoice->total;
            $customer->save();

            // تحديث إجمالي الفاتورة بعد جمع المرتجعات

        });

        return redirect()->route('returns.index', ['customer_id' => $request->customer_id])->with('success',  __('admin.Created Successfully'));
    }


    public function show(ReturnItem $returnItem)
    {
        return view('admin.returns.show', compact('returnItem'));
    }


    public function edit($invoice)
    {
        // return $invoice->returnItems;
        $invoice = Invoice::FindOrFail($invoice);

        $customer = Customer::where('id', $invoice->customer_id)->first();

        $branches  = Branch::all();
        $users  = User::all();
        return view('admin.returns.edit', compact(
            'invoice',
            'customer',
            'branches',
            'users',
        ));
    }

    public function update(Request $request, $id)
    {
        // return $request->paid;
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'branch_id'   => 'required|exists:branches,id',
            'return_qty.*' => 'required|numeric',
            'return_price.*' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $id) {
            // استرجاع الرصيد السابق
            $invoice = Invoice::findOrFail($id);
            $customer = Customer::findOrFail($request->customer_id);

            // 1️⃣ إعادة العميل للوضع السابق قبل تعديل الفاتورة
            $customer->price += abs($invoice->total);
            $customer->save();


            $totalInvoice = 0;

            foreach ($request->return_product_item_id as $key => $productItemId) {
                $qty = $request->return_qty[$key] ?? 0;
                $price = $request->return_price[$key] ?? 0;

                if (!$productItemId || $qty == 0) continue;

                $product = ProductItem::find($productItemId);
                $returnItem = $invoice->returnItems()->where('product_item_id', $productItemId)->first();

                if ($returnItem) {
                    // استرجاع المخزون القديم قبل التعديل
                    if ($product) {
                        $product->qty += $returnItem->qty;
                    }

                    $itemTotal = $qty * $price;

                    $returnItem->update([
                        'qty' => $qty,
                        'price' => $price,
                        'total' => $itemTotal,
                    ]);

                    if ($product) {
                        $product->qty = max(0, $product->qty - $qty);
                        $product->save();
                    }

                    $totalInvoice += $itemTotal;
                } else {
                    // إضافة عنصر جديد
                    $itemTotal = $qty * $price;

                    ReturnItem::create([
                        'invoice_id' => $invoice->id,
                        'customer_id' => $customer->id,
                        'product_item_id' => $productItemId,
                        'qty' => $qty,
                        'price' => $price,
                        'total' => $itemTotal,

                    ]);

                    if ($product) {
                        $product->qty = max(0, $product->qty - $qty);
                        $product->save();
                    }

                    $totalInvoice += $itemTotal;
                }
            }

            // 2️⃣ تحديث الفاتورة بالإجمالي الجديد
            $invoice->update([
                'branch_id' => $request->branch_id,
                'total' =>  $request->net,
                'paid' => $request->paid,
                'remaining' => max(0, $request->net - $request->paid),
                'note' => $request->note,
            ]);

            // 3️⃣ تحديث رصيد العميل بناءً على الإجمالي الجديد
            $customer->price -= $totalInvoice;
            $customer->save();
        });

        return redirect()->route('returns.index', ['customer_id' => $request->customer_id])->with('success',  __('admin.Updated Successfully'));
    }



    public function destroy(Request $request)
    {
        $ids = explode(',', $request->id);

        DB::transaction(function () use ($ids) {
            $invoices = Invoice::whereIn('id', $ids)->get();

            foreach ($invoices as $invoice) {
                // إعادة الرصيد للعميل
                $customer = $invoice->customer;
                if ($customer) {
                    $customer->price += abs($invoice->total);
                    $customer->save();
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
