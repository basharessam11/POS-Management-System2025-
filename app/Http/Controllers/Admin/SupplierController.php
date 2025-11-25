<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCrudPermissions;
use App\Models\ReturnItem;
use App\Models\Supplier;
use App\Models\SupplierInvoice;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    use HasCrudPermissions;

    public function __construct()
    {
        $this->applyCrudPermissions('suppliers');
    }
    public function index(Request $request)
    {
        $search = $request->input('search');

        $suppliers = Supplier::when($search, function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        })->latest()->paginate(20)->appends($request->query());


        // $totalAmount = 
        // $totalReturn = ReturnItem::where('supplier_id', $id)->sum('total');
        // $monthlyAmount = SupplierInvoice::where('supplier_id', $id)->sum('paid');
        // $todayAmount = SupplierInvoice::where('supplier_id', $id)->sum('remaining');;

        return view('admin.suppliers.index', compact('suppliers', 'search'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        Supplier::create([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('suppliers.index')
            ->with('success', __('admin.Created Successfully'));
    }



    /**
     * Display the specified resource.
     */
    public function show(supplier $supplier)
    {
        return view('admin.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }


    // تحديث بيانات المورد
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $supplier = Supplier::findOrFail($id);

        $supplier->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('suppliers.index')
            ->with('success', __('admin.Updated Successfully'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {


        $ex = explode(',', $request->id);

        Supplier::destroy($ex);

        session()->flash('success', __('admin.Deleted Successfully'));

        return redirect()->back();
    }
}
