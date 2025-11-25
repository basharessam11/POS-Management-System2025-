<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCrudPermissions;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    use HasCrudPermissions;

    public function __construct()
    {
        $this->applyCrudPermissions('categories');
    }
    public function index(Request $request)
    {
        $search = $request->input('search');

        $category = Category::when($search, function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        })->latest()->paginate(20)->appends($request->query());

        return view('admin.category.index', compact('category', 'search'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255|unique:categories,name',


        ]);

        Category::create([
            'name'    => $request->name,


        ]);

        return redirect()->route('category.index')
            ->with('success', __('admin.Created Successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(category $category)
    {
        return view('admin.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, category $category)
    {
        $request->validate([
            'name'    => 'required|string|max:255|unique:categories,name,' . $category->id,


        ]);

        $category->update([
            'name'    => $request->name,


        ]);


        return redirect()->route('category.index')->with('success', __('admin.Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {


        $ex = explode(',', $request->id);

        Category::destroy($ex);

        session()->flash('success', __('admin.Deleted Successfully'));

        return redirect()->back();
    }
}
