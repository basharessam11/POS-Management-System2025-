<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCrudPermissions;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    use HasCrudPermissions;

    public function __construct()
    {
        $this->applyCrudPermissions('customers');
    }
    public function index(Request $request)
    {
        $search = $request->input('search');

        $customer = Customer::when($search, function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%");
        })->latest()->paginate(20)->appends($request->query());

        return view('admin.customer.index', compact('customer', 'search'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customer.create');
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

        Customer::create([
            'name'    => $request->name,
            'phone'    => $request->phone,
            'address'    => $request->address,


        ]);

        return redirect()->route('customer.index')
            ->with('success', __('admin.Created Successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return view('admin.customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('admin.customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('customers')->ignore($customer->id)->whereNull('deleted_at'),
            ],
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:255',
            'address' => 'required|string|max:255',

        ]);

        $customer->update([
            'name'    => $request->name,
            'phone'    => $request->phone,
            'address'    => $request->address,


        ]);


        return redirect()->route('customer.index')->with('success', __('admin.Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {


        $ex = explode(',', $request->id);

        Customer::destroy($ex);

        session()->flash('success', __('admin.Deleted Successfully'));

        return redirect()->back();
    }
}
