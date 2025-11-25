<?php $__env->startSection('page', __('admin.Supplier Payment Management')); ?>

<?php $__env->startSection('contant'); ?>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">

                        <div class="card-header">
                            <h5 class="card-title m-0"><?php echo __('admin.Add Payment'); ?></h5>
                        </div>

                        <div class="card-body">

                            
                            <?php if(session('success')): ?>
                                <div class="alert alert-success text-center"><?php echo e(session('success')); ?></div>
                            <?php endif; ?>
                            <?php if(session('error')): ?>
                                <div class="alert alert-danger text-center"><?php echo e(session('error')); ?></div>
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

                            <form action="<?php echo e(route('supplier_payments.store')); ?>" method="POST">
                                <?php echo csrf_field(); ?>

                                <div class="row g-3">
                                    
                                    <div class="col-12">
                                        <label class="form-label" for="supplier_id"><?php echo e(__('admin.Supplier')); ?> <span
                                                class="text-danger">*</span></label>
                                        
                                        <select name="supplier_id" id="supplier_id"
                                            class="form-control select2 <?php $__errorArgs = ['supplier_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            required>
                                            <option value=""><?php echo __('admin.select'); ?>

                                            </option>

                                            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($supplier1->id); ?>"
                                                    <?php echo e(old('supplier_id', request('supplier_id') ?? null) == $supplier1->id ? 'selected' : ''); ?>>
                                                    <?php echo e($supplier1->name); ?> (ID: <?php echo e($supplier1->id); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>

                                        <?php $__errorArgs = ['supplier_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    
                                    <div class="col-md-6 col-12">
                                        <label class="form-label" for="type"><?php echo e(__('admin.Payment Type')); ?> <span
                                                class="text-danger">*</span></label>
                                        <select name="type" id="type"
                                            class="form-control <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="receive" <?php echo e(old('type') == 'receive' ? 'selected' : ''); ?>>
                                                <?php echo e(__('admin.Receive')); ?>

                                            </option>
                                            <option selected value="pay" <?php echo e(old('type') == 'pay' ? 'selected' : ''); ?>>
                                                <?php echo e(__('admin.Pay')); ?>

                                            </option>


                                        </select>
                                        <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    
                                    
                                    <div class="col-md-6 col-12">
                                        <label class="form-label" for="amount"><?php echo e(__('admin.Amount Paid')); ?> <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="amount" id="amount"
                                            value="<?php echo e(old('amount', $supplier->total ?? 0)); ?>" step="1"
                                            placeholder="<?php echo e(__('admin.Amount Paid')); ?>" required min="1">
                                        <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    
                                    <div class="col-12">
                                        <label class="form-label" for="note"><?php echo e(__('admin.Notes')); ?></label>
                                        <textarea name="note" id="note" class="form-control <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            placeholder="<?php echo e(__('admin.Notes')); ?>"><?php echo e(old('note')); ?></textarea>
                                        <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    
                                    <div class="col-12 mt-4 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class='bx bx-plus'></i> <?php echo e(__('admin.Save')); ?>

                                        </button>
                                        <a href="<?php echo e(route('supplier_payments.index', ['supplier_id' => $supplier->id ?? null])); ?>"
                                            class="btn btn-outline-secondary"><?php echo e(__('admin.Cancel')); ?></a>
                                    </div>

                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\laravel_test\pos\resources\views/admin/supplier_payment/create.blade.php ENDPATH**/ ?>