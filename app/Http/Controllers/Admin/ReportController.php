<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCrudPermissions;
use App\Models\Brand;
use App\Models\Expanse;
use App\Models\Invoice;
use App\Models\ProductItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    use HasCrudPermissions;

    public function __construct()
    {
        $this->middleware('can:view product_reports')->only(['product']);
        $this->middleware('can:view income_reports')->only(['income']);
        $this->middleware('can:view product_item_reports')->only(['product_item']);
    }


    /**
     * Display a listing of the resource.
     */
    public function product(Request $request)
    {
        $search = $request->input('search');
        $brand_id = $request->input('brand_id');

        $brands = Brand::all();

        $products = ProductItem::with(['product.brand', 'product.category'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('barcode', 'LIKE', "%{$search}%")                       // بحث بالباركود
                        ->orWhereHas('product', function ($p) use ($search) {          // بحث باسم المنتج
                            $p->where('name', 'LIKE', "%{$search}%")
                                ->orWhereHas('brand', function ($b) use ($search) {    // بحث بالماركة
                                    $b->where('name', 'LIKE', "%{$search}%");
                                })
                                ->orWhereHas('category', function ($c) use ($search) { // بحث بالفئة
                                    $c->where('name', 'LIKE', "%{$search}%");
                                });
                        });
                });
            })
            ->when($brand_id && $brand_id != 'all', function ($query) use ($brand_id) {
                $query->whereHas('product.brand', function ($q) use ($brand_id) {
                    $q->where('id', $brand_id);  // فلترة حسب الماركة إذا مش "all"
                });
            })
            ->latest()
            ->paginate(100)
            ->appends($request->query());

        return view('admin.report.product', compact('products', 'search', 'brands', 'brand_id'));
    }

    public function product_item(Request $request)
    {
        $search = $request->input('search');
        $brand_id = $request->input('brand_id');

        $brands = Brand::all();

        $products = ProductItem::with(['product.brand', 'product.category'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('barcode', 'LIKE', "%{$search}%")                       // بحث بالباركود
                        ->orWhereHas('product', function ($p) use ($search) {          // بحث باسم المنتج
                            $p->where('name', 'LIKE', "%{$search}%")
                                ->orWhereHas('brand', function ($b) use ($search) {    // بحث بالماركة
                                    $b->where('name', 'LIKE', "%{$search}%");
                                })
                                ->orWhereHas('category', function ($c) use ($search) { // بحث بالفئة
                                    $c->where('name', 'LIKE', "%{$search}%");
                                });
                        });
                });
            })
            ->when($brand_id && $brand_id != 'all', function ($query) use ($brand_id) {
                $query->whereHas('product.brand', function ($q) use ($brand_id) {
                    $q->where('id', $brand_id);  // فلترة حسب الماركة إذا مش "all"
                });
            })
            ->latest()
            ->paginate(100)
            ->appends($request->query());

        return view('admin.report.product_item', compact('products', 'search', 'brands', 'brand_id'));
    }


    public function income(Request $request)
    {
        $year = $request->input('year', date('Y')); // السنة الافتراضية السنة الحالية



        // مصفوفة جاهزة لكل الأشهر
        $months = [];
        $income = [];
        $expanses = [];
        for ($i = 1; $i <= 12; $i++) {
            // إجمالي المبيعات لكل شهر
            $months[$i] = Invoice::whereMonth('created_at', $i)
                ->whereYear('created_at', $year)
                ->sum('total');
            $income[$i] = Invoice::whereMonth('created_at', $i)
                ->whereYear('created_at', $year)
                ->sum('income');
            $expanses[$i] = Expanse::whereMonth('created_at', $i)
                ->whereYear('created_at', $year)
                ->sum('price');
        }



        $currentMonth = Carbon::now()->month;
        $currentYear  = Carbon::now()->year;

        // اليوم الحالي
        $todayDay = Carbon::now()->day;

        $currentWeek = Carbon::now()->weekOfYear;

        $today = Invoice::whereYear('created_at', $currentYear)
            ->whereDay('created_at', $todayDay)
            ->sum('income') - Expanse::whereYear('created_at', $currentYear)
            ->whereDay('created_at', $todayDay)
            ->sum('price');

        $yearly = Invoice::whereYear('created_at', $currentYear)
            ->whereYear('created_at', $year)
            ->sum('income') - Expanse::whereYear('created_at', $year)
            ->sum('price');


        $today2 = Expanse::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->whereDay('created_at', $todayDay)
            ->sum('price');

        $yearly2 = Expanse::whereYear('created_at', $year)
            ->sum('price');



        return view('admin.report.income', compact('months', 'year', 'today', 'today2', 'yearly', 'yearly2', 'income', 'expanses'));
    }
}
