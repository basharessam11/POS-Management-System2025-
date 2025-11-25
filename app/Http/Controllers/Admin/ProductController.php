<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCrudPermissions;
use App\Models\Branch;

use App\Models\ProductItem;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    use HasCrudPermissions;


    public function __construct()
    {
        $this->applyCrudPermissions('products');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $brand_id = $request->input('brand_id');
        $brands = Brand::all();

        $products = Product::with(['category', 'brand'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%") // بحث باسم المنتج
                        ->orWhereHas('items', function ($item) use ($search) {
                            $item->where('barcode', 'LIKE', "%{$search}%"); // بحث بالباركود من product_items
                        });
                });
            })
            ->when($brand_id && $brand_id != 'all', function ($query) use ($brand_id) {
                $query->where('brand_id', $brand_id); // فلترة حسب الماركة إذا مش "all"
            })
            ->latest()
            ->paginate(20)
            ->appends($request->query());

        return view('admin.products.index', compact('products', 'search', 'brands', 'brand_id'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $sizes = ['S', 'M', 'L', 'XL', 'XXL']; // كل المقاسات
        $warehouses = \App\Models\Warehouse::all();
        $branches = \App\Models\Branch::all();

        return view('admin.products.create', compact('categories', 'brands', 'sizes', 'warehouses', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate product
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'sizes.*' => 'required|string|max:50',
            'colors.*' => 'required|string|max:50',
            'prices.*' => 'required|numeric|min:0',
            'sell_prices.*' => 'required|numeric|min:0',
            'qtys.*' => 'required|integer|min:0',
            'min_qtys.*' => 'required|integer|min:0',
            'photo.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
        ]);

        foreach ($request->sizes as $index => $size) {

            $pro = ProductItem::create([
                'product_id' => $product->id,
                'size' => $size,
                'color' => $request->colors[$index],
                'price' => $request->prices[$index],
                'sell_price' => $request->sell_prices[$index],
                'sell_price2' => $request->sell_prices2[$index] ?? null,
                'qty' => $request->qtys[$index],
                'min_qty' => $request->min_qtys[$index],
                'photo' =>  null, // ← هنا يتم تخزين الصورة أو null
            ]);
            if ($request->hasFile('photo') && $request->file('photo')[$index]) {

                $pro->setImageAttribute([$request->file('photo')[$index], 'photo']);
                $pro->save();
            }
            // بعد إنشاء الـ ProductItem //
            Stock::create(['warehouse_id' => $request->warehouse_id, 'product_id' => $product->id, 'branch_id' => $request->branch_id, 'product_item_id' => $pro->id, 'qty' => $request->qtys[$index],]);
        }



        return redirect()->back()->with('success', __('admin.Created Successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $warehouses = Warehouse::all();
        $branches = Branch::all();

        // تحميل العناصر والـ stocks الخاصة بكل عنصر


        return view('admin.products.edit', compact(
            'product',
            'categories',
            'brands',
            'warehouses',
            'branches'
        ));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {


        // return $request;
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($product->id)->whereNull('deleted_at'),
            ],


            'category_id'  => 'required|exists:categories,id',
            'brand_id'     => 'required|exists:brands,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'branch_id'    => 'required|exists:branches,id',
            'sizes.*'      => 'required|string|max:50',
            'colors.*'     => 'required|string|max:50',
            'prices.*'     => 'required|numeric|min:0',
            'sell_prices.*' => 'required|numeric|min:0',
            'qtys.*'       => 'required|integer|min:0',
            'min_qtys.*'   => 'required|integer|min:0',

            'item_ids.*'   => 'nullable|integer|exists:product_items,id', // IDs للصفوف القديمة
        ]);

        // return $request ;
        // تحديث بيانات المنتج
        $product->update([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'brand_id'    => $request->brand_id,
        ]);

        $sizes       = $request->sizes;
        $colors      = $request->colors;
        $prices      = $request->prices;
        $sell_prices = $request->sell_prices;
        $sell_prices2 = $request->sell_prices2;
        $qtys        = $request->qtys;
        $min_qtys    = $request->min_qtys;

        $itemIds     = $request->item_ids ?? [];

        // حذف العناصر القديمة اللي مش موجودة في الفورم
        $product->items()->whereNotIn('id', $itemIds)->delete();
        $photoFiles = $request->file('photo') ?? []; // مصفوفة الصور أو فارغة

        foreach ($sizes as $index => $size) {
            if (isset($itemIds[$index])) {
                // تحديث عنصر قديم
                $item = $product->items()->find($itemIds[$index]);
                $item->update([
                    'size'       => $size,
                    'color'      => $colors[$index],
                    'price'      => $prices[$index],
                    'sell_price' => $sell_prices[$index],
                    'sell_price2' => $sell_prices2[$index],
                    'qty'        => $qtys[$index],
                    'min_qty'    => $min_qtys[$index],
                ]);
            } else {
                // إنشاء عنصر جديد
                $item = $product->items()->create([
                    'size'       => $size,
                    'color'      => $colors[$index],
                    'price'      => $prices[$index],
                    'sell_price' => $sell_prices[$index],
                    'sell_price2' => $sell_prices2[$index],

                    'qty'        => $qtys[$index],
                    'min_qty'    => $min_qtys[$index],
                ]);
            }

            // Stock
            $stock = $item->stocks()->firstOrNew([
                'warehouse_id' => $request->warehouse_id,
                'product_id' => $product->id,
                'branch_id'    => $request->branch_id,
                'qty'        => $qtys[$index],
            ]);
            $stock->save();

            if (isset($photoFiles[$index]) && $photoFiles[$index]->isValid()) {
                // حذف الصورة القديمة لو موجودة

                if ($item->photo && file_exists(public_path('images/' . $item->photo))) {
                    Storage::disk('expenses')->delete($item->photo);
                }
                $item->setImageAttribute([$request->file('photo')[$index], 'photo']);
                $item->save();
            }
        }

        return redirect()->route('products.index')->with('success', __('admin.Updated Successfully'));
    }




    public function barcode($id)
    {

        $products = ProductItem::where('product_id', $id)->get();
        return view('admin.products.barcode', compact('products'));
    }


    public function print(Request $request)
    {
        // Validate input
        $request->validate([
            'product_id' => 'required|array',
            'qty'        => 'required|array',
        ]);

        // return $request;
        // Get products
        $products = ProductItem::whereIn('id', $request->product_id)->get();

        // Save quantities
        $qty = $request->qty;

        return view('admin.products.print', compact('products', 'qty'));
    }
    // public function print(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required|array',
    //         'qty' => 'required|array',
    //     ]);

    //     $products = Product::whereIn('id', $request->product_id)->get();


    //     return $request;
    //     return view('admin.products.print', [
    //         'products' => $products,
    //         'qty' => $request->qty
    //     ]);
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ids = explode(',', $request->id);

        // Soft Delete Products and their items
        Product::whereIn('id', $ids)->each(function ($product) {
            $product->items()->delete(); // Soft delete items
            $product->delete();           // Soft delete product
        });

        return redirect()->back()->with('success', __('admin.Deleted Successfully'));
    }
}
