<?php $__env->startSection('page', 'home'); ?>


<?php $__env->startSection('contant'); ?>



    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">




            <!-- Order List Widget -->

            <div class="card mb-4">
                <div class="card-widget-separator-wrapper">
                    <div class="card-body card-widget-separator">
                        <div class="row gy-4 gy-sm-1">
                            <div class="col-sm-6 col-lg-3">
                                <div
                                    class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                    <div>
                                        <h3 class="mb-2">EGP <?php echo e($total); ?></h3>
                                        <p class="mb-0"> <?php echo __('admin.Total'); ?></p>
                                    </div>
                                    <div class="avatar me-sm-4">
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="bx bx-calendar bx-sm"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none me-4">
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div
                                    class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                                    <div>
                                        <h3 class="mb-2"><?php echo e($month); ?></h3>
                                        <p class="mb-0"><?php echo __('admin.Monthly'); ?></p>
                                    </div>
                                    <div class="avatar me-lg-4">
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="bx bx-check-double bx-sm"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none">
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div
                                    class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                    <div>
                                        <h3 class="mb-2"><?php echo e($today); ?></h3>
                                        <p class="mb-0"><?php echo __('admin.Today'); ?></p>
                                    </div>
                                    <div class="avatar me-sm-4">
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="bx bx-wallet bx-sm"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h3 class="mb-2"><?php echo e($yesterday); ?></h3>
                                        <p class="mb-0"><?php echo __('admin.Yesterday'); ?></p>
                                    </div>
                                    <div class="avatar">
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="bx bx-error-alt bx-sm"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product List Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"> <?php echo __('admin.Expenses'); ?></h5>

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

                <div class="card-datatable table-responsive">

                    <div id="products-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="card-header d-flex border-top rounded-0 flex-wrap py-md-0">


                            



                            <form method="GET" action="<?php echo e(route('expenses.index')); ?>">
                                <div class="row g-2 mb-4"> <!-- أضف g-2 لزيادة المسافات -->

                                    <!-- البحث -->
                                    <div class="col-12 col-md-6 col-lg-4 d-flex align-items-end">


                                        <input type="search" name="search" value="<?php echo e(request('search')); ?>"
                                            class="form-control " placeholder="بحث " aria-controls="products-table">
                                    </div>

                                    <!-- التاريخ من -->
                                    <div class="col-12 col-md-6 col-lg-3">
                                        <label class="form-label">من</label>
                                        <input type="date" name="from_date" value="<?php echo e(request('from_date')); ?>"
                                            class="form-control">
                                    </div>

                                    <!-- التاريخ إلى -->
                                    <div class="col-12 col-md-6 col-lg-3">
                                        <label class="form-label">إلى</label>
                                        <input type="date" name="to_date" value="<?php echo e(request('to_date')); ?>"
                                            class="form-control">
                                    </div>

                                    <!-- زر البحث -->
                                    <div class="col-12 col-md-6 col-lg-2 d-flex align-items-end">
                                        <button type="submit"
                                            class="btn btn-primary w-100 mt-4"><?php echo __('admin.Submit'); ?></button>
                                    </div>

                                </div>
                            </form>

                            







                            



                            <div class="d-flex justify-content-start justify-content-md-end align-items-baseline">
                                <div
                                    class="dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0">

                                    <div class="dt-buttons btn-group flex-wrap">


                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['delete expenses'])): ?>
                                            <button class="btn btn-secondary add-new btn-danger de me-3" tabindex="0"
                                                aria-controls="products-table" type="button" data-bs-toggle="modal"
                                                data-bs-target="#basicModal2" style="display:none"><span><i
                                                        class="bx bx-trash"></i><span
                                                        class="d-none d-sm-inline-block"><?php echo e(__('admin.Delete')); ?>

                                                    </span></span></button>
                                        <?php endif; ?>


                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['create expenses'])): ?>
                                            <a href="<?php echo e(route('expenses.create')); ?>">
                                                <button class="btn btn-secondary add-new btn-primary ms-2" tabindex="0"
                                                    aria-controls="products-table" type="button" data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasEcommerceCategoryList"><span><i
                                                            class="bx bx-plus me-0 me-sm-1"></i><?php echo __('admin.Add Expenses'); ?></span></button>

                                            </a>
                                        <?php endif; ?>









                                    </div>
                                </div>
                            </div>
                            


                        </div>


                    </div>




















                    <table id="products-table" class="datatables-products table border-top dataTable no-footer dtr-column">
                        <thead>





                            <tr>
                                <th></th>
                                <th></th>

                                <th><?php echo __('admin.Note'); ?></th>
                                <th><?php echo __('admin.Price'); ?></th>
                                <th><?php echo __('admin.Date'); ?></th>



                                <th class="text-lg-center"><?php echo __('admin.Actions'); ?></th>
                            </tr>



                            </tr>
                        </thead>
                        <tbody>
                            
                            

                            <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" value="<?php echo e($expense->id); ?>"
                                            onclick="data('dt-checkboxes')" class="dt-checkboxes form-check-input">

                                    </td>
                                    <td>
                                        <input type="checkbox" value="<?php echo e($expense->id); ?>"
                                            onclick="data('dt-checkboxes')" class="dt-checkboxes form-check-input">

                                    </td>


                                    <td><?php echo e($expense->note); ?></td>
                                    <td>EGP <?php echo e(number_format($expense->price)); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($expense->created_at)->format('Y-m-d')); ?></td>

                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['edit expenses'])): ?>
                                                <a href="<?php echo e(route('expenses.edit', $expense->id)); ?>">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <div class="row mx-2">
                        <?php echo e($expenses->links('vendor.pagination.bootstrap-5')); ?>

                    </div>
                    <script>
                        $(document).ready(function() {
                            var table = $('#products-table').DataTable({
                                columnDefs: [{
                                        className: "control",
                                        searchable: false,
                                        orderable: false,
                                        responsivePriority: 2,
                                        targets: 0,
                                        render: function(t, e, s, a) {
                                            // console.log(s)
                                            return ""
                                        }

                                    },
                                    {
                                        targets: 1,

                                        checkboxes: {
                                            selectAllRender: '<input type="checkbox" onclick="data1(`all`)" class="all form-check-input">'
                                        },
                                        render: function(t, e, s, a) {
                                            // console.log(s[0])
                                            return s[0];
                                        },
                                        searchable: !1
                                    }
                                ],


                                responsive: {
                                    details: {
                                        display: $.fn.dataTable.Responsive.display.modal({
                                            header: function(row) {
                                                return 'تفاصيل ' + row.data()[
                                                    1]; // عرض اسم العميل في عنوان المودال
                                            }
                                        }),
                                        type: "column",
                                        renderer: function(api, rowIdx, columns) {
                                            var data = $.map(columns, function(col, i) {
                                                return col.title ?
                                                    `<tr><td><strong>${col.title}:</strong></td><td>${col.data}</td></tr>` :
                                                    '';
                                            }).join('');
                                            return data ? $('<table class="table"/>').append('<tbody>' + data +
                                                '</tbody>') : false;
                                        }
                                    }
                                },
                                paging: false, // 🚫 إيقاف الباجيناشن
                                info: false, // 🚫 إخفاء "Showing X to Y of Z entries"
                                ordering: true,
                                searching: false
                            });
                        });
                    </script>

                </div>
                <br>
                <br>
            </div>

        </div>
        <!-- / Content -->





        

        <div class="modal fade" id="basicModal2" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1 " data-i18n="Delete">Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form method="POST" action="<?php echo e(route('expenses.destroy', 0)); ?>">
                                <?php echo method_field('delete'); ?>
                                <?php echo csrf_field(); ?>
                                <div id="name" class=" col mb-3">

                                    <?php echo e(__('admin.Are you sure you want to delete?')); ?>


                                </div>
                                <input class="val" type="hidden" name="id">


                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            data-i18n="Close">Close</button>
                        <button type="submit" class="btn btn-danger" data-i18n="Delete">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        





    <?php $__env->stopSection(); ?>



    <?php $__env->startSection('footer'); ?>

    <?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\laravel_test\pos\resources\views/admin/expenses/index.blade.php ENDPATH**/ ?>