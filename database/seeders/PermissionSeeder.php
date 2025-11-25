<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. تحديد جميع الوحدات (Modules) بشكل نهائي ومتوافق مع المسارات
        $modules = [
            'users',
            'roles',
            'warehouses',
            'branches', // تم التعديل من 'branch' إلى 'branches'
            'products',
            'returns', // مرتجعات العملاء
            'suppliers',
            'supplier_returns', // مرتجعات الموردين
            'supplier_invoice',
            'supplier_payments',
            'brands',
            'categories', // تم التعديل من 'category' إلى 'categories'
            'expenses',
            'invoices',
            'customer_payments',
            'customers',
            'settings',
        ];

        // 2. الإجراءات القياسية (Standard CRUD Actions)
        $standardActions = ['view', 'show', 'create', 'edit', 'delete'];


        $permissionsToCreate = [];

        // 3. إنشاء الصلاحيات القياسية لكل Module
        foreach ($modules as $module) {
            foreach ($standardActions as $action) {
                $permissionsToCreate[] = "$action $module";
            }
        }

        // 4. إضافة الصلاحيات الخاصة/المخصصة (Special Permissions)
        $specialPermissions = [
            // لوحة القيادة
            'view dashboard',

            // المنتجات
            'print barcode products',

            // الفواتير والمرتجعات
            'print invoices',
            'print2 invoices', // print3 في الـ route لكن print2 في الـ actions
            'print supplier_returns',
            'print2 supplier_returns',
            'print supplier_invoice',
            'print2 supplier_invoice',

            // التقارير (تم فصلها كوحدات)
            'view product_reports',
            'view income_reports',
        ];

        $permissionsToCreate = array_merge($permissionsToCreate, $specialPermissions);

        // 5. حفظ جميع الصلاحيات في قاعدة البيانات
        foreach (array_unique($permissionsToCreate) as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // ============ Roles Setup ============

        // مسح جميع الصلاحيات المرتبطة بالـ Roles القديمة قبل إعادة التعيين
        Role::whereIn('name', ['super-admin', 'inventory-manager', 'cashier'])->get()->each(function ($role) {
            $role->syncPermissions([]);
        });


        // 1- Super Admin: كل الصلاحيات
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        // 2- Inventory Manager: مسؤول المخزون والموردين
        $inventoryManagerRole = Role::firstOrCreate(['name' => 'inventory-manager', 'guard_name' => 'web']);
        $inventoryPermissions = [
            'view dashboard',
            'view customers',
            'view customer_payments',
            'view invoices',

            // الإدارة الكاملة للمخزون
            'view warehouses',
            'create warehouses',
            'edit warehouses',
            'delete warehouses',
            'view products',
            'create products',
            'edit products',
            'delete products',
            'print barcode products',
            'view brands',
            'create brands',
            'edit brands',
            'delete brands',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',

            // الإدارة الكاملة للموردين
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers',
            'view supplier_invoice',
            'create supplier_invoice',
            'edit supplier_invoice',
            'delete supplier_invoice',
            'print supplier_invoice',
            'print2 supplier_invoice',
            'view supplier_returns',
            'create supplier_returns',
            'edit supplier_returns',
            'delete supplier_returns',
            'print supplier_returns',
            'print2 supplier_returns',
            'view supplier_payments',
            'create supplier_payments',
            'edit supplier_payments',
            'delete supplier_payments',

            // التقارير
            'view product_reports',
        ];
        $inventoryManagerRole->syncPermissions($inventoryPermissions);

        // 3- Cashier/Sales: مسؤول المبيعات والعملاء
        $cashierRole = Role::firstOrCreate(['name' => 'cashier', 'guard_name' => 'web']);
        $cashierPermissions = [
            'view dashboard',

            // الإدارة الكاملة للمبيعات
            'view invoices',
            'create invoices',
            'edit invoices',
            'print invoices',
            'print2 invoices',
            'view returns',
            'create returns',
            'edit returns',
            'view customers',
            'create customers',
            'edit customers',
            'view customer_payments',
            'create customer_payments',
            'edit customer_payments',

            // عرض المنتجات فقط
            'view products',
            'view brands',
            'view categories',
            'view warehouses',
        ];
        $cashierRole->syncPermissions($cashierPermissions);
    }
}
