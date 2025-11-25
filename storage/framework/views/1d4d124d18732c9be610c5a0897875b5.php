<?php
    use App\Models\Setting;
?>


<?php $__env->startSection('page', 'Order List'); ?>


<?php $__env->startSection('contant'); ?>




    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">


            <div class="card" style="padding:40px; text-align:center;">
                <h2 style="font-size:30px; margin-bottom:15px;">
                    <?php echo e(__('admin.welcome_dashboard')); ?>


                </h2>

                <p style="font-size:18px; color:#666; margin-bottom:25px;">
                    <?php echo e(__('admin.happy_to_have_you')); ?>


                </p>

                <img src="<?php echo e(asset('images')); ?>/<?php echo e(Setting::find(1)->photo != null ? Setting::find(1)->photo : 'no-image.png'); ?>"
                    style="max-width:260px; margin:auto;" alt="welcome">
            </div>

        </div>
        <!-- / Content -->



    <?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\laravel_test\pos\resources\views/dashboard.blade.php ENDPATH**/ ?>