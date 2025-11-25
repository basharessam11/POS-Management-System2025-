<?php $__env->startSection('page', 'Order List'); ?>


<?php $__env->startSection('contant'); ?>




    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">





            <div class="row g-4">


                <?php echo $__env->make('admin.layout.menu-slider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <!-- Options -->
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="tab-content p-0">
                        <!-- Store Details Tab -->
                        <div class="tab-pane fade show active" id="store_details" role="tabpanel">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title m-0"><?php echo __('admin.Settings'); ?></h5>
                                </div>
                                <div class="card-body">
                                    


                                    <?php if(session('success')): ?>
                                        <div id="success-message"
                                            class="alert alert-success alert-dismissible fade show text-center"
                                            role="alert">
                                            <?php echo e(session('success')); ?>

                                        </div>
                                    <?php endif; ?>

                                    <?php if(session('error')): ?>
                                        <div id="danger-message"
                                            class="alert alert-danger alert-dismissible fade show text-center"
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
                                    


                                    <form action="<?php echo e(route('settings.update', 1)); ?>" method="post"
                                        enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>

                                        <div class="row mb-3 g-3">
                                            <!-- Store Name -->
                                            <div class="col-12 col-md-12">
                                                <label class="form-label mb-0"
                                                    for="store-name"><?php echo __('admin.Store Name'); ?></label>
                                                <input type="text" class="form-control" id="store-name"
                                                    value="<?php echo e($settings->name ?? ''); ?>" placeholder="Store Name"
                                                    name="name" aria-label="Store Name">
                                            </div>

                                            <!-- Store Phone -->
                                            <div class="col-12 col-md-12">
                                                <label class="form-label mb-0"
                                                    for="store-Phone"><?php echo __('admin.Phone'); ?></label>
                                                <input type="text" class="form-control" id="store-Phone"
                                                    value="<?php echo e($settings->phone ?? ''); ?>" placeholder="Store Phone"
                                                    name="phone" aria-label="Store Phone">
                                            </div>

                                            <!-- Store Location -->
                                            <div class="col-12 col-md-12">
                                                <label class="form-label mb-0"
                                                    for="store-Location"><?php echo __('admin.Location'); ?></label>
                                                <input type="text" class="form-control" id="store-Location"
                                                    value="<?php echo e($settings->location ?? ''); ?>" placeholder="Store Location"
                                                    name="location" aria-label="Store Location">
                                            </div>

                                            <!-- Photo -->
                                            <div class="col-12 col-md-12">
                                                <label class="form-label mb-0"
                                                    for="photo"><?php echo __('admin.Photo'); ?></label>
                                                <input type="file" class="form-control" id="photo" name="photo"
                                                    aria-label="Store Photo">
                                                <img style="width: 120px;height:auto"
                                                    src="<?php echo e(asset('images')); ?>/<?php echo e($settings->photo != null ? $settings->photo : 'no-image.png'); ?>"
                                                    alt="">
                                            </div>

                                            <!-- Save and Discard buttons -->





                                        </div>
                                        <div class="d-flex justify-content-end gap-3">

                                            <button type="submit" class="btn btn-primary"><?php echo __('admin.Submit'); ?>

                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Options -->





    <?php $__env->stopSection(); ?>

    <?php $__env->startSection('footer'); ?>

        <!-- Page JS -->
        <script src="<?php echo e(asset('admin') ?? ''); ?>/js/app-ecommerce-settings.js"></script>

    <?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\laravel_test\pos\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>