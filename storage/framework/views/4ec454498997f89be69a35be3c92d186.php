<!-- Menu -->
<?php
    use App\Models\Setting;
?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <div class="app-brand demo ">
        <a class="app-brand-link">
            <span class="app-brand-logo demo">
                <img style="width: 45px; height:auto"
                    src="<?php echo e(asset('images')); ?>/<?php echo e(Setting::find(1)->photo != null ? Setting::find(1)->photo : 'no-image.png'); ?>"
                    class="ms-auto" alt="logo" width="30" />
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2"><?php echo e(Setting::find(1)->name); ?></span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        
        
       
            <li class="menu-item <?php echo e(request()->is('/') || request()->routeIs('dashboard') ? 'active open' : ''); ?>">
                <a href="<?php echo e(route('dashboard')); ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-home"></i>
                    <div class="text-truncate"><?php echo e(__('admin.Dashboards')); ?></div>
                </a>
            </li>
        








        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view customers', 'view customer_returns', 'view customer_invoice', 'view customer_payments'])): ?>
            <li
                class="menu-item <?php echo e(in_array(Request::route()->getName(), ['customer.index', 'invoice.index', 'customer_payments.index', 'returns.index']) ? 'active open' : ''); ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon bx bxs-user-account'></i>
                    <div class="text-truncate"><?php echo __('admin.Customer'); ?></div>
                </a>
                <ul class="menu-sub">

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view customers')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'customer.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('customer.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo __('admin.Customer'); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view invoices')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'invoice.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('invoice.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo __('admin.Invoice'); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view customer_payments')): ?>
                        <li
                            class="menu-item <?php echo e(Request::route()->getName() == 'customer_payments.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('customer_payments.index')); ?>" class="menu-link">
                                <div class="text-truncate"> <?php echo e(__('admin.Customer Payments')); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>


                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view returns')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'returns.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('returns.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo e(__('admin.Customer Returns')); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>


                </ul>
            </li>
        <?php endif; ?>




        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view products', 'view brands', 'view categories'])): ?>
            <li
                class="menu-item <?php echo e(in_array(Request::route()->getName(), ['products.index', 'brand.index', 'category.index']) ? 'active open' : ''); ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bxs-box'></i>
                    <div class="text-truncate"><?php echo __('admin.Products'); ?></div>
                </a>
                <ul class="menu-sub">
                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view products')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'products.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('products.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo __('admin.Products'); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>


                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view brands')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'brand.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('brand.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo __('admin.Brand'); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>



                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view categories')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'category.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('category.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo __('admin.Category'); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>





        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view suppliers', 'view supplier_returns', 'view supplier_invoice', 'view supplier_payments'])): ?>
            <li
                class="menu-item <?php echo e(in_array(Request::route()->getName(), ['suppliers.index', 'supplier_returns.index', 'supplier_invoice.index', 'supplier_payments.index']) ? 'active open' : ''); ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bxs-group'></i>
                    <div class="text-truncate"><?php echo e(__('admin.Suppliers and Purchases')); ?></div>
                </a>
                <ul class="menu-sub">
                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view suppliers')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'suppliers.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('suppliers.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo e(__('admin.Suppliers')); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view supplier_invoice')): ?>
                        <li
                            class="menu-item <?php echo e(Request::route()->getName() == 'supplier_invoice.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('supplier_invoice.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo e(__('admin.Supplier Invoices')); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view supplier_payments')): ?>
                        <li
                            class="menu-item <?php echo e(Request::route()->getName() == 'supplier_payments.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('supplier_payments.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo e(__('admin.Supplier Payments')); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>


                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view supplier_returns')): ?>
                        <li
                            class="menu-item <?php echo e(Request::route()->getName() == 'supplier_returns.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('supplier_returns.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo e(__('admin.Supplier Returns')); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>





        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view expenses')): ?>
            <li class="menu-item <?php echo e(Request::route()->getName() == 'expenses.index' ? 'active open' : ''); ?>">
                <a href="<?php echo e(route('expenses.index')); ?>" class="menu-link">
                    <i class='menu-icon bx bxs-wallet'></i>
                    <div class="text-truncate"><?php echo __('admin.Expenses'); ?></div>
                </a>
            </li>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view product_reports', 'view income_reports'])): ?>
            <li
                class="menu-item <?php echo e(in_array(Request::route()->getName(), ['report.product', 'report.product_item', 'report.income']) ? 'active open' : ''); ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bxs-report'></i>
                    <div class="text-truncate"><?php echo e(__('admin.Reports')); ?></div>
                </a>
                <ul class="menu-sub">

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view product_reports')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'report.product' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('report.product')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo e(__('admin.Product Inventory Report')); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>



                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view product_reports')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'report.product_item' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('report.product_item')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo e(__('admin.Product Movement Reports')); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>






                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view income_reports')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'report.income' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('report.income')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo e(__('admin.Income and Profit Report')); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>






                </ul>
            </li>
        <?php endif; ?>


        
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view settings')): ?>
            <li class="menu-item <?php echo e(Request::route()->getName() == 'settings.backup' ? 'active open' : ''); ?>">
                <a href="<?php echo e(route('settings.backup')); ?>" class="menu-link">
                    <i class='menu-icon bx bxs-cloud-download'></i>
                    <div class="text-truncate"><?php echo e(__('admin.Backup')); ?></div>
                </a>
            </li>
        <?php endif; ?>


        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view users', 'view roles'])): ?>
            <li
                class="menu-item <?php echo e(in_array(Request::route()->getName(), ['users.index', 'roles.index']) ? 'active open' : ''); ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bxs-user'></i>
                    <div class="text-truncate"><?php echo e(__('admin.Employees')); ?></div>
                </a>
                <ul class="menu-sub">

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view users')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'users.index' ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('users.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo e(__('admin.Employees')); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view roles')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'roles.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('roles.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo __('admin.Roles'); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>






        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view settings', 'view warehouses', 'view branches'])): ?>
            <li
                class="menu-item <?php echo e(in_array(Request::route()->getName(), ['settings.index', 'warehouses.index', 'branch.index']) ? 'active open' : ''); ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bxs-box'></i>
                    <div class="text-truncate"><?php echo __('admin.Settings'); ?></div>
                </a>
                <ul class="menu-sub">
                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view settings')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'settings.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('settings.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo __('admin.Settings'); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>


                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view warehouses')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'warehouses.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('warehouses.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo __('admin.Warehouses'); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view branches')): ?>
                        <li class="menu-item <?php echo e(Request::route()->getName() == 'branch.index' ? 'active open' : ''); ?>">
                            <a href="<?php echo e(route('branch.index')); ?>" class="menu-link">
                                <div class="text-truncate"><?php echo __('admin.Branch'); ?></div>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </li>
        <?php endif; ?>


    </ul>

</aside>
<!-- / Menu -->
<?php /**PATH D:\xampp\htdocs\laravel_test\pos\resources\views/admin/layout/menu.blade.php ENDPATH**/ ?>