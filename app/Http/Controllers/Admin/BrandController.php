<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCrudPermissions;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use HasCrudPermissions;

    public function __construct()
    {
        $this->applyCrudPermissions('brands');
    }
    public function index(Request $request)
    {
        $search = $request->input('search');

        $brand = Brand::when($search, function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        })->latest()->paginate(20)->appends($request->query());

        return view('admin.brand.index', compact('brand', 'search'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255|unique:brands,name',


        ]);

        Brand::create([
            'name'    => $request->name,


        ]);

        return redirect()->route('brand.index')
            ->with('success', __('admin.Created Successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(brand $brand)
    {
        return view('admin.brand.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(brand $brand)
    {
        return view('admin.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, brand $brand)
    {
        $request->validate([
            'name'    => 'required|string|max:255|unique:brands,name,' . $brand->id,


        ]);

        $brand->update([
            'name'    => $request->name,


        ]);


        return redirect()->route('brand.index')->with('success', __('admin.Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {


        $ex = explode(',', $request->id);

        Brand::destroy($ex);

        session()->flash('success', __('admin.Deleted Successfully'));

        return redirect()->back();
    }
}
