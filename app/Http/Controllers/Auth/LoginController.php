<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Redirect user after login based on permissions.
     */
    protected function authenticated($request, $user)
    {
        // // جلب كل الصلاحيات للمستخدم
        // $permissions = $user->getAllPermissions()->pluck('name')->toArray();

        // // خريطة الصفحات حسب الصلاحية
        // $redirectMap = [
        //     'view users'       => route('users.index'),
        //     'view roles'       => route('roles.index'),
        //     'view invoices'    => route('invoice.index'),
        //     'view customers'   => route('customer.index'),
        //     'view products'    => route('products.index'),
        //     'view brands'      => route('brand.index'),
        //     'view categories'  => route('category.index'),
        //     'view warehouses'  => route('warehouses.index'),
        //     'view branches'    => route('branch.index'),
        //     'view expenses'    => route('expenses.index'),
        //     'view settings'    => route('settings.index'),
        // ];

        // // البحث عن أول صلاحية موجودة في الماب
        // foreach ($permissions as $permission) {
        //     if (isset($redirectMap[$permission])) {
        //         return redirect($redirectMap[$permission]);
        //     }
        // }

        return redirect()->route('index');
    }
}
