<?php $__env->startSection('page', 'Order List'); ?>


<?php $__env->startSection('contant'); ?>




    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">



            <!-- Product List Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"> <?php echo __('admin.Employees'); ?></h5>

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


                                



                                <form method="GET" action="<?php echo e(route('users.index')); ?>">
                                    <div class="row g-2 mb-4 mt-2"> <!-- أضف g-2 لزيادة المسافات -->

                                        <!-- البحث -->
                                        <div class="col-12 col-md-8 col-lg-8 d-flex align-items-end">


                                            <input type="search" name="search" value="<?php echo e(request('search')); ?>"
                                                class="form-control " placeholder="بحث " aria-controls="products-table">
                                        </div>



                                        <!-- زر البحث -->
                                        <div class="col-12 col-md-4 col-lg-4 d-flex align-items-end">
                                            <button type="submit"
                                                class="btn btn-primary w-100 mt-6"><?php echo __('admin.Submit'); ?></button>
                                        </div>

                                    </div>
                                </form>

                                






                                



                                <div class="d-flex justify-content-start justify-content-md-end align-items-baseline">
                                    <div
                                        class="dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0">

                                        <div class="dt-buttons btn-group flex-wrap">



                                            <a href="<?php echo e(route('users.create')); ?>">
                                                <button class="btn btn-secondary add-new btn-success ms-2" tabindex="0"
                                                    aria-controls="products-table" type="button" data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasEcommerceCategoryList"><span><i
                                                            class="bx bx-plus me-0 me-sm-1"></i><?php echo __('admin.Add Employees'); ?></span></button>

                                            </a>



                                        </div>
                                    </div>
                                </div>
                                


                            </div>


                        </div>
                        <table id="products-table"
                            class="datatables-products table border-top dataTable no-footer dtr-column">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th><?php echo e(__('admin.Employees')); ?></th>
                                    <th><?php echo e(__('admin.Country')); ?> </th>
                                    <th class="text-nowrap text-center align-middle"> <?php echo __('admin.Join_Date'); ?> </th>


                                    <th class="text-nowrap text-center align-middle"><?php echo e(__('admin.Roles')); ?></th>
                                    <th class="text-nowrap text-center align-middle"><?php echo e(__('admin.Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                

                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="odd">


                                        <td>
                                            <input type="checkbox" value="<?php echo e($user->id); ?>"
                                                onclick="data('dt-checkboxes')" class="dt-checkboxes form-check-input">

                                        </td>
                                        <td>
                                            <input type="checkbox" value="<?php echo e($user->id); ?>"
                                                onclick="data('dt-checkboxes')" class="dt-checkboxes form-check-input">

                                        <td class="text-nowrap text-center align-middle">
                                            <div class="d-flex justify-content-start align-items-center customer-name">
                                                <div class="avatar-wrapper">
                                                    <?php if($user->photo != null): ?>
                                                        <div class="avatar-wrapper">
                                                            <div class="avatar me-2">
                                                                <img class="img-fluid"
                                                                    src="<?php echo e(asset('images/' . $user->photo)); ?>"
                                                                    style="height: 100%; width: 100%; object-fit: cover; border-radius: 50%;"
                                                                    alt="User avatar">

                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <!-- عرض أول حرفين من الاسم عند عدم وجود صورة -->
                                                        <?php
                                                            $nameInitials = mb_substr($user->name, 0, 2, 'UTF-8'); // استخراج أول حرفين
                                                        ?>

                                                        <div class="avatar me-2">
                                                            <span class="avatar-initial rounded-circle bg-label-secondary">
                                                                <?php echo e($nameInitials); ?>

                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <!-- معلومات العميل -->
                                                <div class="d-flex flex-column">
                                                    <a href="<?php echo e(route('users.edit', $user->id)); ?>">
                                                        <span class="fw-medium"><?php echo e($user->name); ?></span>
                                                    </a>
                                                    <small class="text-muted text-nowrap"><?php echo e($user->phone); ?></small>

                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-nowrap text-center align-middle">

                                            <div class="d-flex justify-content-start align-items-center customer-country">
                                                <div><i
                                                        class="fis fi fi-<?php echo e($user->country->code); ?> rounded-circle me-2 fs-3"></i>
                                                </div>
                                                <div><span><?php echo e($user->country->name); ?></span></div>
                                            </div>
                                        </td>

                                        <td class="text-center align-middle">
                                            <span class="fw-medium"><?php echo e($user->join_date); ?></span>
                                        </td>

                                        <td class="text-center align-middle">
                                            <span class="fw-medium">
                                                <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge bg-primary"><?php echo e($role->name); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </span>
                                        </td>
                                        <td class="text-nowrap text-center align-middle">
                                            <div class="d-inline-block text-nowrap">
                                                <a href="<?php echo e(route('users.edit', $user->id)); ?>">
                                                    <button class="btn btn-sm btn-icon" title="<?php echo e(__('admin.Edit')); ?>">
                                                        <i class="bx bx-edit"></i>
                                                    </button>
                                                </a>
                                            </div>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <div class="row mx-2">
                            <?php echo e($users->links('vendor.pagination.bootstrap-5')); ?>

                        </div>
                        <script>
                            $(document).ready(function() {
                                var table = $('#products-table').DataTable({
                                    columnDefs: [{
                                            className: "control",
                                            searchable: false,
                                            orderable: false,
                                            targets: 0,

                                            responsivePriority: 2,

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
        </div>
        <!-- / Content -->


        

        <div class="modal fade" id="basicModal2" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1 " data-i18n="<?php echo e(__('admin.Delete')); ?>">
                            <?php echo e(__('admin.Delete')); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form method="POST" action="<?php echo e(route('users.destroy', 0)); ?>">
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
                        <button type="submit" class="btn btn-danger"
                            data-i18n="<?php echo e(__('admin.Delete')); ?>"><?php echo e(__('admin.Delete')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        



    <?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\laravel_test\pos\resources\views/admin/users/index.blade.php ENDPATH**/ ?>