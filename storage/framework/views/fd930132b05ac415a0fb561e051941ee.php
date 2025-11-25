<?php $__env->startSection('page', 'Order List'); ?>


<?php $__env->startSection('contant'); ?>




    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Order List Widget -->


            <!-- Product List Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"> <?php echo __('admin.Products'); ?></h5>
                    <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">


                        


                        <?php if(session('success')): ?>
                            <div id="success-message" class="alert alert-success alert-dismissible fade show text-center"
                                role="alert">
                                <?php echo e(session('success')); ?>

                            </div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                            <div id="danger-message" class="alert alert-danger alert-dismissible fade show text-center"
                                role="alert">
                                <?php echo e(session('error')); ?>

                            </div>
                        <?php endif; ?>



                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        


                    </div>

                </div>

                <!-- customers List Table -->
                <div class="card">

                    <div class="card-datatable table-responsive">
                        <div id="products-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="card-header d-flex border-top rounded-0 flex-wrap py-md-0">






                                <form method="GET" action="<?php echo e(route('report.product_item')); ?>">
                                    <div class="row g-3 align-items-end mb-4">
                                        <!-- البحث -->
                                        <div class="col-12 col-md-4">
                                            <label class="form-label"><?php echo __('admin.Search'); ?></label>
                                            <input type="search" name="search" value="<?php echo e(request('search')); ?>"
                                                class="form-control" placeholder="<?php echo __('admin.Search'); ?>"
                                                aria-controls="products-table">
                                        </div>

                                        <!-- الماركة -->
                                        <div class="col-12 col-md-6">
                                            <label class="form-label"><?php echo __('admin.Brand'); ?></label>
                                            <select class="form-control select2" name="brand_id">
                                                <option value="all"><?php echo __('admin.select'); ?> <?php echo __('admin.All'); ?>

                                                </option>

                                                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($brand->id); ?>"
                                                        <?php echo e(request('brand_id') == $brand->id ? 'selected' : ''); ?>>
                                                        <?php echo e($brand->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        <!-- زر البحث -->
                                        <div class="col-12 col-md-2">
                                            <button type="submit" class="btn btn-primary w-100 mt-2">
                                                <?php echo __('admin.Submit'); ?>

                                            </button>
                                        </div>
                                    </div>
                                </form>


                                



                                <div class="d-flex justify-content-start justify-content-md-end align-items-baseline">
                                    <div
                                        class="dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0">

                                        <div class="dt-buttons btn-group flex-wrap">
                                            <!-- زر الطباعة -->
                                            <button class="btn btn-primary mb-3 mt-3" onclick="printProductsTable()">
                                                🖨️ <?php echo __('admin.Print Table'); ?>

                                            </button>


                                        </div>
                                    </div>
                                </div>
                                


                            </div>

                        </div>
                        <div class="table-responsive">
                            <table id="products-table"
                                class="datatables-products table table-bordered border-top dataTable no-footer dtr-column">
                                <thead>
                                    <tr>
                                        <th style="min-width: 50px;">#</th>
                                        <th style="min-width: 50px;"><?php echo e(__('admin.Barcode')); ?></th>
                                        <th style="min-width: 170px;"><?php echo e(__('admin.Name')); ?></th>
                                        <th style="min-width: 60px;"><?php echo e(__('admin.Remaining Quantity')); ?></th>
                                        <th style="min-width: 60px;"><?php echo e(__('admin.Quantity Sold')); ?></th>
                                        <th style="min-width: 170px;"><?php echo e(__('admin.Total Sales')); ?></th>
                                        <th style="min-width: 170px;"><?php echo e(__('admin.Net Profit')); ?></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="odd">
                                            <td><?php echo e($products->firstItem() + $index); ?></td>
                                            <td><?php echo e($product->barcode); ?></td>

                                            <td><?php echo e($product->product->name . ' / ' . $product->product->category->name . ' / ' . $product->product->brand->name . ' / ' . $product->size); ?>

                                            </td>

                                            <td>
                                                <div class="alert <?php if($product->qty <= $product->min_qty): ?> alert-danger <?php endif; ?>">
                                                    <?php echo e($product->qty); ?>

                                                </div>
                                            </td>

                                            <td>
                                                <div class="alert alert-success">
                                                    <?php echo e($qty = $product->invoice_items->sum('qty')); ?>

                                                </div>
                                            </td>

                                            <td>
                                                <div class="alert alert-primary">
                                                    <?php
                                                        $retailTotal1 = $product->invoice_items
                                                            ->where('type', 1)
                                                            ->sum('total');
                                                        $wholesaleTotal1 = $product->invoice_items
                                                            ->where('type', 2)
                                                            ->sum('total');
                                                    ?>

                                                    <strong><?php echo e(__('admin.Total Retail:')); ?></strong> <?php echo e($retailTotal1); ?>

                                                    <br>
                                                    <strong><?php echo e(__('admin.Total Wholesale:')); ?></strong>
                                                    <?php echo e($wholesaleTotal1); ?> <br>
                                                    <strong><?php echo e(__('admin.Grand Total:')); ?></strong>
                                                    <?php echo e($retailTotal1 + $wholesaleTotal1); ?>

                                                </div>
                                            </td>

                                            <td>
                                                <div class="alert alert-success">
                                                    <?php
                                                        $retailIncome = $product->invoice_items
                                                            ->where('type', 1)
                                                            ->sum('income');
                                                        $wholesaleIncome = $product->invoice_items
                                                            ->where('type', 2)
                                                            ->sum('income');
                                                    ?>

                                                    <strong><?php echo e(__('admin.Total Retail:')); ?></strong> <?php echo e($retailIncome); ?>

                                                    <br>
                                                    <strong><?php echo e(__('admin.Total Wholesale:')); ?></strong>
                                                    <?php echo e($wholesaleIncome); ?> <br>
                                                    <strong><?php echo e(__('admin.Grand Total:')); ?></strong>
                                                    <?php echo e($retailIncome + $wholesaleIncome); ?>

                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mx-2">
                            <?php echo e($products->links('vendor.pagination.bootstrap-5')); ?>

                        </div>

                    </div>
                    <br>
                    <br>
                </div>

            </div>
        </div>
        <!-- / Content -->

        <script>
            function printProductsTable() {
                // الحصول على محتوى الجدول
                const tableContent = document.getElementById('products-table').outerHTML;

                // فتح نافذة جديدة للطباعة
                const printWindow = window.open('', '', 'width=1000,height=700');

                // كتابة المحتوى في صفحة الطباعة
                printWindow.document.write(`
        <html dir="rtl" lang="ar">
        <head>
            <title><?php echo e(__('admin.Print Products Report')); ?></title>
            <style>
                body { font-family: 'Arial', sans-serif; direction: rtl; text-align: center; margin: 20px; }
                h2 { margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                th { background-color: #f2f2f2; font-weight: bold; }
                tr:nth-child(even) { background-color: #f9f9f9; }
            </style>
        </head>
        <body>
            <h2><?php echo e(__('admin.Products Report')); ?></h2>
            ${tableContent}
        </body>
        </html>
    `);

                // إغلاق المستند وطباعة الصفحة
                printWindow.document.close();
                printWindow.print();
            }
        </script>



    <?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\laravel_test\pos\resources\views/admin/report/product_item.blade.php ENDPATH**/ ?>