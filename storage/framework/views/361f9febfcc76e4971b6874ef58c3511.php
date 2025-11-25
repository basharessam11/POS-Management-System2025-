<?php $__env->startSection('page', 'Create Product'); ?>


<?php $__env->startSection('contant'); ?>








    




    
    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">



            <form action="<?php echo e(route('expenses.store')); ?>" method="post" enctype="multipart/form-data">
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
                                    <h5 class="card-tile mb-0"><?php echo __('admin.Add Expenses'); ?></h5>
                                </div>
                                <div class="card-body">



                                    
                                    <div>
                                        <label class="form-label"><?php echo __('admin.Note'); ?></label>


                                        <textarea class=" form-control" name="note" placeholder="اكتب هنا "><?php echo e(old('note')); ?></textarea>




                                        <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <br>
                                            <div class="alert alert-danger"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>



                                    </div>

                                    
                                    
                                    <div class="mb-3">
                                        <br>
                                        <label class="form-label"><?php echo __('admin.Price'); ?></label>
                                        <input type="number" class="form-control" value="<?php echo e(old('price')); ?>"
                                            name="price" required step="0.01">





                                        <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <br>
                                            <div class="alert alert-danger"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>



                                    </div>

                                    



                                    




                                    <div>

                                        


                                        




                                        <button type="submit" class="btn btn-primary"><?php echo __('admin.Submit'); ?></button>
                                    </div>
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


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\laravel_test\pos\resources\views/admin/expenses/create.blade.php ENDPATH**/ ?>