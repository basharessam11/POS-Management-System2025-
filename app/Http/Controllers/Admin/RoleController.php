<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCrudPermissions;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    use HasCrudPermissions;


    public function __construct()
    {
        $this->applyCrudPermissions('roles');
    }

    /**
     * عرض جميع Roles.
     */
    public function index()
    {
        return view('admin.roles.index');
    }

    /**
     * بيانات الـ DataTables.
     */
    public function data()
    {
        $roles = Role::orderBy('created_at', 'desc')->get();

        return DataTables::of($roles)
            ->make(true);
    }

    /**
     * نموذج إنشاء Role جديد.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * حفظ Role جديد.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($request->permissions);

        session()->flash('success', __('admin.Created Successfully'));
        return redirect()->route('roles.index');
    }

    /**
     * نموذج تعديل Role.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        // تجميع الأذونات حسب الصفحة (مثل booking, meeting, contact ...)
        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            $parts = explode(' ', $permission->name);
            $action = array_shift($parts);
            $module = implode(' ', $parts);

            if (!isset($groupedPermissions[$module])) {
                $groupedPermissions[$module] = [
                    'module' => $module,
                    'actions' => [],
                ];
            }

            $groupedPermissions[$module]['actions'][$action] = $permission->name;
        }

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions', 'groupedPermissions'));
    }

    /**
     * تحديث Role.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'array',
        ]);

        $role->name = $request->name;
        $role->save();

        $role->syncPermissions($request->permissions ?? []);

        session()->flash('success', __('admin.Updated Successfully'));
        return redirect()->route('roles.index');
    }

    /**
     * حذف Role.
     */
    public function destroy(Request $request)
    {
        $ids = explode(',', $request->id);
        Role::whereIn('id', $ids)->delete();

        session()->flash('success', __('admin.Deleted Successfully'));
        return redirect()->route('roles.index');
    }
}
