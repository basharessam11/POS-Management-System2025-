<?php $__env->startSection('page', 'Create Product'); ?>


<?php $__env->startSection('contant'); ?>








    




    
    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">



            <form action="<?php echo e(route('roles.store')); ?>" method="post" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="app-ecommerce">

                    <!-- Add Product -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">




                    </div>

                    <div class="row">

                        <!-- First column-->
                        <div class="col-12 col-lg-12">
                            <!-- Product Information -->
                            <div class="card mb-12">
                                <div class="card-header">
                                    <h5 class="card-tile mb-0"><?php echo __('admin.Add Roles'); ?></h5>
                                    


                                    <?php if(session('success')): ?>
                                        <br>
                                        <div id="success-message"
                                            class="alert alert-success alert-dismissible fade show text-center"
                                            role="alert">
                                            <?php echo e(session('success')); ?>

                                        </div>
                                    <?php endif; ?>

                                    <?php if(session('error')): ?>
                                        <br>

                                        <div id="danger-message"
                                            class="alert alert-danger alert-dismissible fade show text-center"
                                            role="alert">
                                            <?php echo e(session('error')); ?>

                                        </div>
                                    <?php endif; ?>



                                    <?php if($errors->any()): ?>
                                        <br>

                                        <div class="alert alert-danger">
                                            <ul>
                                                
                                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($error); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                    


                                </div>
                                <div class="card-body">








                                    
                                    <div class="mb-3">
                                        <label class="form-label"><?php echo __('admin.Name'); ?></label>
                                        <input type="text" class="form-control" required id="ecommerce-product-name"
                                            value="<?php echo e(old('name')); ?>" placeholder=" <?php echo __('admin.Name'); ?>"
                                            name="name" aria-label="Product title">


                                    </div>

                                    



                                    <table class="table table-flush-spacing mb-0 border-top">
                                        <tbody>
                                            <tr>
                                                <td class="text-nowrap fw-medium text-heading"> <?php echo e(__('admin.Actions')); ?> <i
                                                        class="icon-base bx bx-info-circle" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        aria-label="Allows a full access to the system"
                                                        data-bs-original-title="Allows a full access to the system"></i>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-end">
                                                        <div class="form-check mb-0">
                                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                                            <label class="form-check-label" for="selectAll">
                                                                <?php echo e(__('admin.Select All')); ?>

                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>


                                            <?php
                                                // تجميع الأذونات حسب الصفحة (مثل booking, meeting, contact, ...)
                                                $groupedPermissions = [];

                                                // dd($permissions);
                                                foreach ($permissions as $permission) {
                                                    $parts = explode(' ', $permission->name); // تقسيم الاسم إلى أجزاء
                                                    $action = array_shift($parts); // أول جزء (view, create, edit, delete)
                                                    $module = implode(' ', $parts); // باقي الاسم (مثل booking, meeting)

                                                    if (!isset($groupedPermissions[$module])) {
                                                        $groupedPermissions[$module] = [
                                                            'module' => $module,
                                                            'actions' => [],
                                                        ];
                                                    }

                                                    $groupedPermissions[$module]['actions'][$action] =
                                                        $permission->name;
                                                }

                                            ?>



                                            <?php $__currentLoopData = $groupedPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                
                                                <tr>
                                                    <td class="text-nowrap fw-medium text-heading">



                                                        <?php echo e(__('admin.' . ucfirst($group['module']))); ?>



                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-end">
                                                            <div class="form-check mb-0 me-3 me-lg-12">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="permissions[]"
                                                                    value="<?php echo e($group['actions']['view'] ?? ''); ?>"
                                                                    <?php echo e(isset($group['actions']['view']) ? '' : 'disabled'); ?>>
                                                                <label class="form-check-label">
                                                                    <?php echo e(__('admin.View')); ?>


                                                                </label>
                                                            </div>
                                                            <div class="form-check mb-0 me-3 me-lg-12">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="permissions[]"
                                                                    value="<?php echo e($group['actions']['create'] ?? ''); ?>"
                                                                    <?php echo e(isset($group['actions']['create']) ? '' : 'disabled'); ?>>
                                                                <label class="form-check-label">
                                                                    <?php echo e(__('admin.Add')); ?>

                                                                </label>
                                                            </div>

                                                            <div class="form-check mb-0 me-3 me-lg-12">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="permissions[]"
                                                                    value="<?php echo e($group['actions']['show'] ?? ''); ?>"
                                                                    <?php echo e(isset($group['actions']['show']) ? '' : 'disabled'); ?>>
                                                                <label class="form-check-label">
                                                                    <?php echo e(__('admin.Show')); ?>

                                                                </label>
                                                            </div>

                                                            <div class="form-check mb-0 me-3 me-lg-12">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="permissions[]"
                                                                    value="<?php echo e($group['actions']['edit'] ?? ''); ?>"
                                                                    <?php echo e(isset($group['actions']['edit']) ? '' : 'disabled'); ?>>
                                                                <label class="form-check-label">
                                                                    <?php echo e(__('admin.Edit')); ?>


                                                                </label>
                                                            </div>

                                                            <div class="form-check mb-0 me-3 me-lg-12">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="permissions[]"
                                                                    value="<?php echo e($group['actions']['delete'] ?? ''); ?>"
                                                                    <?php echo e(isset($group['actions']['delete']) ? '' : 'disabled'); ?>>
                                                                <label class="form-check-label">
                                                                    <?php echo e(__('admin.Delete')); ?>

                                                                </label>
                                                            </div>
                                                            <div class="form-check mb-0 me-3 me-lg-12">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="permissions[]"
                                                                    value="<?php echo e($group['actions']['print'] ?? ''); ?>"
                                                                    <?php echo e(isset($group['actions']['print']) ? '' : 'disabled'); ?>>
                                                                <label class="form-check-label">
                                                                    <?php echo e(__('admin.Print Retail')); ?>

                                                                </label>
                                                            </div>
                                                            <div class="form-check mb-0 me-3 me-lg-12">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="permissions[]"
                                                                    value="<?php echo e($group['actions']['print2'] ?? ''); ?>"
                                                                    <?php echo e(isset($group['actions']['print2']) ? '' : 'disabled'); ?>>
                                                                <label
                                                                    class="form-check-label"><?php echo e(__('admin.Print Wholesale')); ?>

                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>




                                        </tbody>
                                    </table>
                                    <br>
                                    <br>

                                    <button type="submit" class="btn btn-primary"><?php echo __('admin.Submit'); ?> </button>
                                </div>








                            </div>



            </form>
        </div>



        <!-- /Organize Card -->
    </div>
    <!-- /Second column -->
    </div>
    </div>
    </div>
    <!-- / Content -->



    </form>












<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <script src="<?php echo e(asset('admin')); ?>/js/app-ecommerce-product-add.js"></script>

    <!-- Page JS -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectAllCheckbox = document.getElementById("selectAll");
            const allCheckboxes = document.querySelectorAll(".form-check-input:not(#selectAll)");

            selectAllCheckbox.addEventListener("change", function() {
                allCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });

            // تحديث "Select All" إذا تم إلغاء تحديد أحد العناصر
            allCheckboxes.forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    if (!checkbox.checked) {
                        selectAllCheckbox.checked = false;
                    } else if ([...allCheckboxes].every(chk => chk.checked)) {
                        selectAllCheckbox.checked = true;
                    }
                });
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\laravel_test\pos\resources\views/admin/roles/create.blade.php ENDPATH**/ ?>