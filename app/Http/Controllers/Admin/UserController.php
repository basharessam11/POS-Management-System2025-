<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Traits\HasCrudPermissions;
use App\Models\Country;


use App\Models\User;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    use HasCrudPermissions;


    public function __construct()
    {
        $this->applyCrudPermissions('users');
    }
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%");
        }

        if ($request->filled('from_date') && $request->filled('to_date') && $request->from_date <= $request->to_date) {
            $query->whereBetween('join_date', [
                Carbon::parse($request->from_date)->startOfDay(),
                Carbon::parse($request->to_date)->endOfDay()
            ]);
        } elseif ($request->filled('from_date')) {
            $query->whereDate('join_date', '>=', Carbon::parse($request->from_date)->startOfDay());
        } elseif ($request->filled('to_date')) {
            $query->whereDate('join_date', '<=', Carbon::parse($request->to_date)->endOfDay());
        }

        $user = Auth::user();

        if (!$user->hasRole('admin')) {

            $query->where('id',  $user->id);
        }
        $users = $query->with('country:id,name,code')->orderBy('created_at', 'desc')->paginate(20)->appends($request->query());

        return view('admin.users.index', get_defined_vars());
    }
    public function show(string $id)
    {
        //
    }
    public function create()
    {

        $countries = Country::all();

        $roles = Role::get(['id', 'name']);

        return view('admin.users.create', get_defined_vars());
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->except('photo', 'role'));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        if ($request->hasFile('photo')) {
            $user->setImageAttribute([$request->file('photo')]);
            $user->save();
        }

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', __('admin.Created Successfully'));
    }

    public function edit(string $id)
    {

        $country = Country::all();
        $user = User::FindOrFail($id);
        $countries = Country::all();

        $roles = Role::get(['id', 'name']);

        return view('admin.users.edit', get_defined_vars());
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->except('photo', 'password', 'role'));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('setting')->delete($user->photo);
            }
            $user->setImageAttribute([$request->file('photo')]);
            $user->save();
        }


        $user->save();
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', __('admin.Updated Successfully'));
    }

    public function destroy(Request $request)
    {
        $ex = explode(',', $request->id);

        foreach ($ex as $key => $value) {
            $user = User::findOrFail($value);
            if ($user->photo) {
                Storage::disk('setting')->delete($user->photo);
            }
            $user->delete();
        }

        session()->flash('success', __('admin.Deleted Successfully'));

        return redirect()->back();
    }
}
