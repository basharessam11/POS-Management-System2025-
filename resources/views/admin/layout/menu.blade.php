<!-- Menu -->
@php
    use App\Models\Setting;
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <div class="app-brand demo ">
        <a class="app-brand-link">
            <span class="app-brand-logo demo">
                <img style="width: 45px; height:auto"
                    src="{{ asset('images') }}/{{ Setting::find(1)->photo != null ? Setting::find(1)->photo : 'no-image.png' }}"
                    class="ms-auto" alt="logo" width="30" />
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">{{ Setting::find(1)->name }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- لوحة القيادة - الصلاحية view dashboard --}}
        {{-- سنستخدم @can هنا للتأكد من أن الأدوار المحددة حديثاً لديها وصول --}}
       
            <li class="menu-item {{ request()->is('/') || request()->routeIs('dashboard') ? 'active open' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-home"></i>
                    <div class="text-truncate">{{ __('admin.Dashboards') }}</div>
                </a>
            </li>
        








        {{-- Suppliers Section Dropdown (تم تحديث الصلاحيات) --}}
        @canany(['view customers', 'view customer_returns', 'view customer_invoice', 'view customer_payments'])
            <li
                class="menu-item {{ in_array(Request::route()->getName(), ['customer.index', 'invoice.index', 'customer_payments.index', 'returns.index']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon bx bxs-user-account'></i>
                    <div class="text-truncate">{!! __('admin.Customer') !!}</div>
                </a>
                <ul class="menu-sub">

                    {{-- customer - العملاء --}}
                    @can('view customers')
                        <li class="menu-item {{ Request::route()->getName() == 'customer.index' ? 'active open' : '' }}">
                            <a href="{{ route('customer.index') }}" class="menu-link">
                                <div class="text-truncate">{!! __('admin.Customer') !!}</div>
                            </a>
                        </li>
                    @endcan

                    {{-- Invoice - الفواتير --}}
                    @can('view invoices')
                        <li class="menu-item {{ Request::route()->getName() == 'invoice.index' ? 'active open' : '' }}">
                            <a href="{{ route('invoice.index') }}" class="menu-link">
                                <div class="text-truncate">{!! __('admin.Invoice') !!}</div>
                            </a>
                        </li>
                    @endcan

                    {{-- Customer Payments - مدفوعات العملاء (إضافة هذا البند) --}}
                    @can('view customer_payments')
                        <li
                            class="menu-item {{ Request::route()->getName() == 'customer_payments.index' ? 'active open' : '' }}">
                            <a href="{{ route('customer_payments.index') }}" class="menu-link">
                                <div class="text-truncate"> {{ __('admin.Customer Payments') }}</div>
                            </a>
                        </li>
                    @endcan


                    {{-- Returns (مرتجعات العملاء) --}}
                    @can('view returns')
                        <li class="menu-item {{ Request::route()->getName() == 'returns.index' ? 'active open' : '' }}">
                            <a href="{{ route('returns.index') }}" class="menu-link">
                                <div class="text-truncate">{{ __('admin.Customer Returns') }}</div>
                            </a>
                        </li>
                    @endcan


                </ul>
            </li>
        @endcanany




        {{-- Products Section Dropdown --}}
        @canany(['view products', 'view brands', 'view categories'])
            <li
                class="menu-item {{ in_array(Request::route()->getName(), ['products.index', 'brand.index', 'category.index']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bxs-box'></i>
                    <div class="text-truncate">{!! __('admin.Products') !!}</div>
                </a>
                <ul class="menu-sub">
                    {{-- Products --}}
                    @can('view products')
                        <li class="menu-item {{ Request::route()->getName() == 'products.index' ? 'active open' : '' }}">
                            <a href="{{ route('products.index') }}" class="menu-link">
                                <div class="text-truncate">{!! __('admin.Products') !!}</div>
                            </a>
                        </li>
                    @endcan


                    {{-- Brand --}}
                    @can('view brands')
                        <li class="menu-item {{ Request::route()->getName() == 'brand.index' ? 'active open' : '' }}">
                            <a href="{{ route('brand.index') }}" class="menu-link">
                                <div class="text-truncate">{!! __('admin.Brand') !!}</div>
                            </a>
                        </li>
                    @endcan



                    {{-- Category (تم تحديث الصلاحية) --}}
                    @can('view categories')
                        <li class="menu-item {{ Request::route()->getName() == 'category.index' ? 'active open' : '' }}">
                            <a href="{{ route('category.index') }}" class="menu-link">
                                <div class="text-truncate">{!! __('admin.Category') !!}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany





        {{-- Suppliers Section Dropdown (تم تحديث الصلاحيات) --}}
        @canany(['view suppliers', 'view supplier_returns', 'view supplier_invoice', 'view supplier_payments'])
            <li
                class="menu-item {{ in_array(Request::route()->getName(), ['suppliers.index', 'supplier_returns.index', 'supplier_invoice.index', 'supplier_payments.index']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bxs-group'></i>
                    <div class="text-truncate">{{ __('admin.Suppliers and Purchases') }}</div>
                </a>
                <ul class="menu-sub">
                    {{-- Supplier - الموردون --}}
                    @can('view suppliers')
                        <li class="menu-item {{ Request::route()->getName() == 'suppliers.index' ? 'active open' : '' }}">
                            <a href="{{ route('suppliers.index') }}" class="menu-link">
                                <div class="text-truncate">{{ __('admin.Suppliers') }}</div>
                            </a>
                        </li>
                    @endcan

                    {{-- Supplier Invoice - فواتير المشتريات --}}
                    @can('view supplier_invoice')
                        <li
                            class="menu-item {{ Request::route()->getName() == 'supplier_invoice.index' ? 'active open' : '' }}">
                            <a href="{{ route('supplier_invoice.index') }}" class="menu-link">
                                <div class="text-truncate">{{ __('admin.Supplier Invoices') }}</div>
                            </a>
                        </li>
                    @endcan

                    {{-- Supplier Payments - مدفوعات الموردين --}}
                    @can('view supplier_payments')
                        <li
                            class="menu-item {{ Request::route()->getName() == 'supplier_payments.index' ? 'active open' : '' }}">
                            <a href="{{ route('supplier_payments.index') }}" class="menu-link">
                                <div class="text-truncate">{{ __('admin.Supplier Payments') }}</div>
                            </a>
                        </li>
                    @endcan


                    {{-- Supplier Returns - مرتجعات الموردين --}}
                    @can('view supplier_returns')
                        <li
                            class="menu-item {{ Request::route()->getName() == 'supplier_returns.index' ? 'active open' : '' }}">
                            <a href="{{ route('supplier_returns.index') }}" class="menu-link">
                                <div class="text-truncate">{{ __('admin.Supplier Returns') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany





        {{-- Expenses - المصروفات --}}
        @can('view expenses')
            <li class="menu-item {{ Request::route()->getName() == 'expenses.index' ? 'active open' : '' }}">
                <a href="{{ route('expenses.index') }}" class="menu-link">
                    <i class='menu-icon bx bxs-wallet'></i>
                    <div class="text-truncate">{!! __('admin.Expenses') !!}</div>
                </a>
            </li>
        @endcan

        {{-- Reports Section Dropdown (تم تحديث الصلاحيات) --}}
        @canany(['view product_reports', 'view income_reports'])
            <li
                class="menu-item {{ in_array(Request::route()->getName(), ['report.product', 'report.product_item', 'report.income']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bxs-report'></i>
                    <div class="text-truncate">{{ __('admin.Reports') }}</div>
                </a>
                <ul class="menu-sub">

                    {{-- Product Inventory Report (جرد المنتجات) --}}
                    @can('view product_reports')
                        <li class="menu-item {{ Request::route()->getName() == 'report.product' ? 'active open' : '' }}">
                            <a href="{{ route('report.product') }}" class="menu-link">
                                <div class="text-truncate">{{ __('admin.Product Inventory Report') }}</div>
                            </a>
                        </li>
                    @endcan



                    {{-- Product Item Report (تقارير حركة المنتجات) --}}
                    @can('view product_reports')
                        <li class="menu-item {{ Request::route()->getName() == 'report.product_item' ? 'active open' : '' }}">
                            <a href="{{ route('report.product_item') }}" class="menu-link">
                                <div class="text-truncate">{{ __('admin.Product Movement Reports') }}</div>
                            </a>
                        </li>
                    @endcan






                    {{-- Income Report (الدخل والأرباح) --}}
                    @can('view income_reports')
                        <li class="menu-item {{ Request::route()->getName() == 'report.income' ? 'active open' : '' }}">
                            <a href="{{ route('report.income') }}" class="menu-link">
                                <div class="text-truncate">{{ __('admin.Income and Profit Report') }}</div>
                            </a>
                        </li>
                    @endcan






                </ul>
            </li>
        @endcanany


        {{-- Backup - النسخ الاحتياطي (تحت إعدادات النظام) --}}
        {{-- لا يوجد صلاحية منفصلة للـ backup، بل تتبع صلاحية view settings --}}
        @can('view settings')
            <li class="menu-item {{ Request::route()->getName() == 'settings.backup' ? 'active open' : '' }}">
                <a href="{{ route('settings.backup') }}" class="menu-link">
                    <i class='menu-icon bx bxs-cloud-download'></i>
                    <div class="text-truncate">{{ __('admin.Backup') }}</div>
                </a>
            </li>
        @endcan


        {{-- Employees/Users Section Dropdown --}}
        @canany(['view users', 'view roles'])
            <li
                class="menu-item {{ in_array(Request::route()->getName(), ['users.index', 'roles.index']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bxs-user'></i>
                    <div class="text-truncate">{{ __('admin.Employees') }}</div>
                </a>
                <ul class="menu-sub">

                    {{-- Users --}}
                    @can('view users')
                        <li class="menu-item {{ Request::route()->getName() == 'users.index' ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}" class="menu-link">
                                <div class="text-truncate">{{ __('admin.Employees') }}</div>
                            </a>
                        </li>
                    @endcan

                    {{-- Roles --}}
                    @can('view roles')
                        <li class="menu-item {{ Request::route()->getName() == 'roles.index' ? 'active open' : '' }}">
                            <a href="{{ route('roles.index') }}" class="menu-link">
                                <div class="text-truncate">{!! __('admin.Roles') !!}</div>
                            </a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcanany






        {{-- Products Section Dropdown --}}
        @canany(['view settings', 'view warehouses', 'view branches'])
            <li
                class="menu-item {{ in_array(Request::route()->getName(), ['settings.index', 'warehouses.index', 'branch.index']) ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bxs-box'></i>
                    <div class="text-truncate">{!! __('admin.Settings') !!}</div>
                </a>
                <ul class="menu-sub">
                    {{-- Settings - إعدادات النظام --}}
                    @can('view settings')
                        <li class="menu-item {{ Request::route()->getName() == 'settings.index' ? 'active open' : '' }}">
                            <a href="{{ route('settings.index') }}" class="menu-link">
                                <div class="text-truncate">{!! __('admin.Settings') !!}</div>
                            </a>
                        </li>
                    @endcan


                    {{-- Warehouses - المخازن --}}
                    @can('view warehouses')
                        <li class="menu-item {{ Request::route()->getName() == 'warehouses.index' ? 'active open' : '' }}">
                            <a href="{{ route('warehouses.index') }}" class="menu-link">
                                <div class="text-truncate">{!! __('admin.Warehouses') !!}</div>
                            </a>
                        </li>
                    @endcan

                    {{-- Branches - الفروع (تم تحديث الاسم والصلاحية) --}}
                    @can('view branches')
                        <li class="menu-item {{ Request::route()->getName() == 'branch.index' ? 'active open' : '' }}">
                            <a href="{{ route('branch.index') }}" class="menu-link">
                                <div class="text-truncate">{!! __('admin.Branch') !!}</div>
                            </a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcanany


    </ul>

</aside>
<!-- / Menu -->
