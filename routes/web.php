<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerPaymentController;
use App\Http\Controllers\Admin\ExpensesController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReturnItemController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\SupplierInvoiceController;
use App\Http\Controllers\Admin\SupplierPaymentController;
use App\Http\Controllers\Admin\SupplierReturnController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Models\CustomerPayment;
use App\Models\ProductItem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
######################################################################Admin###############################################################################

########################################index#######################################################################
Route::get('/', function () {
    return redirect()->route('dashboard'); // غير admin.dashboard → dashboard
})->name('index');


########################################end index#######################################################################
Route::middleware(['auth:web'])->prefix('admin')->group(function () {



    Route::get('dashboard', function () {


        return view('dashboard');
    })->name('dashboard');




    ##################################users#####################################

    Route::controller(UserController::class)->group(function () {
        Route::resource('users', UserController::class);
        Route::get('users/data', 'data')->name('users.data');
    });

    ##################################End users#####################################


    ##################################roles#####################################

    Route::controller(RoleController::class)->group(function () {
        Route::get('roles/data', 'data')->name('roles.data');
        Route::resource('roles', RoleController::class);
    });

    ##################################End roles#####################################


    ##################################warehouses#####################################

    Route::resource('warehouses', WarehouseController::class);

    ##################################End warehouses#####################################


    ##################################branch#####################################

    Route::resource('branch', BranchController::class);

    ##################################End branch#####################################



    ##################################product#####################################

    Route::resource('products', ProductController::class);
    Route::get('/barcode/{id}', [ProductController::class, 'barcode'])->name('barcode.barcode');
    Route::post('/barcode/print', [ProductController::class, 'print'])->name('barcode.print');


    ##################################End product#####################################

    ##################################returns#####################################

    Route::resource('/returns', ReturnItemController::class);



    ##################################End returns#####################################
    ##################################Supplier#####################################

    Route::resource('suppliers', SupplierController::class);

    ##################################End Supplier#####################################

    ##################################supplier_returns#####################################

    Route::controller(SupplierReturnController::class)->group(function () {


        Route::resource('supplier_returns', SupplierReturnController::class);


        Route::get('/supplier_returns/{id}/print2',  'print2')->name('supplier_returns.print2');
        Route::get('/supplier_returns/{id}/print3',  'print3')->name('supplier_returns.print3');
    });


    ##################################End supplier_returns#####################################
    ##################################supplier_invoice#####################################

    Route::controller(SupplierInvoiceController::class)->group(function () {


        Route::resource('supplier_invoice', SupplierInvoiceController::class);
        Route::get('/invoice/search1',  'show1')->name('invoice.search1');

        Route::get('/supplier_invoice/{id}/print2',  'print2')->name('supplier_invoice.print2');
        Route::get('/supplier_invoice/{id}/print3',  'print3')->name('supplier_invoice.print3');
    });

    ##################################End supplier_invoice#####################################
    ##################################customerpayment#####################################

    Route::resource('supplier_payments', SupplierPaymentController::class);

    ##################################End customer_payments#####################################
    ##################################brand#####################################

    Route::resource('brand', BrandController::class);

    ##################################End brand#####################################
    ##################################category#####################################

    Route::resource('category', CategoryController::class);

    ##################################End category#####################################


    ##################################Expenses#####################################


    Route::resource('expenses', ExpensesController::class);


    ##################################End Expenses#####################################

    ##################################invoice#####################################

    Route::controller(InvoiceController::class)->group(function () {

        // Route::get('invoice/print/{invoice}', 'print')->name('invoice.print');
        Route::resource('invoice', InvoiceController::class);

        Route::get('/invoice/{id}/print',  'print')->name('invoice.print');
        Route::get('/invoice/{id}/print2',  'print2')->name('invoice.print2');
        Route::get('/invoice/{id}/print3',  'print3')->name('invoice.print3');
        Route::get('/invoice/raw-data/{id}',  'rawData')->name('invoice.rawData');
    });
    // routes/web.php
    Route::get('/invoice/search', [InvoiceController::class, 'show'])->name('invoice.search');



    ##################################End invoice#####################################

    ##################################customerpayment#####################################

    Route::resource('customer_payments', CustomerPaymentController::class);

    ##################################End customer_payments#####################################



    ##################################customer#####################################

    Route::resource('customer', CustomerController::class);

    ##################################End customer#####################################









    ##################################settings#####################################

    Route::controller(SettingController::class)->group(function () {
        Route::get('/settings/backup',   'backup')->name('settings.backup');
        Route::resource('settings', SettingController::class);
    });

    ##################################End settings#####################################





});
######################################################################End Admin###############################################################################



// Route::get('/backup', function () {

//     Artisan::call('db:backup');
//     return redirect()->back()->with('success', __('admin.Created Successfully'));
// });








##################################report#####################################

Route::get('report/product', [ReportController::class, 'product'])->name('report.product');
Route::get('report/product_item', [ReportController::class, 'product_item'])->name('report.product_item');
Route::get('report/income', [ReportController::class, 'income'])->name('report.income');

##################################End report#####################################







Route::get('/test', function () {
    $sizes = ['M', 'L', 'XL', 'XXL', 'XXXL', 'XXXXL'];

    $items = [];

    for ($i = 1; $i <= 10000; $i++) {
        $items[] = [
            'product_id' => 22,
            'barcode' => str_pad($i, 6, '0', STR_PAD_LEFT),
            'size' => $sizes[$i % 6],
            'color' => 'ابيض',
            'price' => 0.00,
            'sell_price' => 50 + ($i % 10) * 5,
            'sell_price2' => 0.00,
            'qty' => 100,
            'min_qty' => 20,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];

        // لتقليل استهلاك الذاكرة، نضيفهم دفعة دفعة كل 500 صف
        if (count($items) == 500) {
            ProductItem::insert($items);
            $items = [];
        }
    }

    // إدخال أي عناصر متبقية
    if (count($items) > 0) {
        ProductItem::insert($items);
    }

    echo "10,000 product items added successfully!\n";
});









Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session()->put('locale', $locale);
    }
    App::setLocale($locale);


    return redirect()->back();
})->name('language');
