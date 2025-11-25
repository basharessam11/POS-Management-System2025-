<?php $__env->startSection('page', __('admin.Edit_Product')); ?>

<?php $__env->startSection('contant'); ?>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0"><?php echo __('admin.Edit'); ?> <?php echo __('admin.Products'); ?></h5>
                        </div>
                        <div class="card-body">

                            
                            <?php if(session('success')): ?>
                                <div class="alert alert-success text-center"><?php echo e(session('success')); ?></div>
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

                            
                            <form action="<?php echo e(route('products.update', $product->id)); ?>" method="post"
                                enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>

                                <div class="row mb-3 g-3">

                                    
                                    <div class="col-12 col-md-12">
                                        <label class="form-label"><?php echo __('admin.Name'); ?></label>
                                        <input type="text" class="form-control" name="name"
                                            value="<?php echo e(old('name', $product->name)); ?>" required>
                                    </div>

                                    
                                    <div class="col-12 col-md-6">
                                        <label class="form-label"><?php echo __('admin.Category'); ?></label>
                                        <select class="form-control select2" name="category_id" required>
                                            <option value=""><?php echo __('admin.select'); ?></option>
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($category->id); ?>"
                                                    <?php echo e(old('category_id', $product->category_id) == $category->id ? 'selected' : ''); ?>>
                                                    <?php echo e($category->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    
                                    <div class="col-12 col-md-6">
                                        <label class="form-label"><?php echo __('admin.Brand'); ?></label>
                                        <select class="form-control select2" name="brand_id" required>
                                            <option value=""><?php echo __('admin.select'); ?></option>
                                            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($brand->id); ?>"
                                                    <?php echo e(old('brand_id', $product->brand_id) == $brand->id ? 'selected' : ''); ?>>
                                                    <?php echo e($brand->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    
                                    <div class="col-12 col-md-6">
                                        <label class="form-label"><?php echo __('admin.Warehouse'); ?></label>
                                        <select name="warehouse_id" class="form-control select2" required>
                                            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($warehouse->id); ?>"
                                                    <?php echo e(old('warehouse_id', $product->warehouse_id) == $warehouse->id ? 'selected' : ''); ?>>
                                                    <?php echo e($warehouse->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    
                                    <div class="col-12 col-md-6">
                                        <label class="form-label"><?php echo __('admin.Branch'); ?></label>
                                        <select name="branch_id" class="form-control select2" required>
                                            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($branch->id); ?>"
                                                    <?php echo e(old('branch_id', $product->branch_id) == $branch->id ? 'selected' : ''); ?>>
                                                    <?php echo e($branch->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    
                                    <div class="col-12">
                                        <h6 class="mt-4"><?php echo __('admin.Item'); ?></h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="productItemsTable">
                                                <thead>
                                                    <tr>
                                                        <th style="min-width: 140px;"><?php echo __('admin.Size'); ?></th>
                                                        <th style="min-width: 100px;"><?php echo __('admin.Color'); ?></th>
                                                        <th style="min-width: 140px;"><?php echo __('admin.Price'); ?></th>
                                                        <th style="min-width: 140px;"><?php echo __('admin.Sell_Price'); ?></th>
                                                        <th style="min-width: 140px;"><?php echo __('admin.Wholesale_Price'); ?></th>
                                                        <th style="min-width: 100px;"><?php echo __('admin.Quantity'); ?></th>
                                                        <th style="min-width: 100px;"><?php echo __('admin.Min Quantity'); ?></th>
                                                        <th style="min-width: 100px;"><?php echo __('admin.Photo'); ?></th>
                                                        <th style="min-width: 90px;"><?php echo __('admin.Delete'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $product->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <input type="hidden" name="item_ids[]"
                                                            value="<?php echo e($item->id); ?>">

                                                        <tr>
                                                            <td>
                                                                <input type="text" name="sizes[]" class="form-control"
                                                                    value="<?php echo e($item->size); ?>">
                                                            </td>
                                                            <td><input type="text" name="colors[]" class="form-control"
                                                                    value="<?php echo e($item->color); ?>" required></td>
                                                            <td><input type="number" min="0" name="prices[]"
                                                                    class="form-control" value="<?php echo e($item->price); ?>"
                                                                    required>
                                                            </td>
                                                            <td><input type="number" min="0" name="sell_prices[]"
                                                                    class="form-control" value="<?php echo e($item->sell_price); ?>"
                                                                    required></td>
                                                            <td><input type="number" min="0" name="sell_prices2[]"
                                                                    class="form-control" value="<?php echo e($item->sell_price2); ?>"
                                                                    required></td>
                                                            <td><input type="number" min="1" name="qtys[]"
                                                                    class="form-control" value="<?php echo e($item->qty); ?>"
                                                                    required>
                                                            </td>
                                                            <td><input type="number" min="1" name="min_qtys[]"
                                                                    class="form-control" value="<?php echo e($item->min_qty); ?>"
                                                                    required>

                                                            </td>
                                                            <td>
                                                                <input type="file" name="photo[]" class="form-control"
                                                                    accept="image/*">
                                                                <?php if($item->photo != null && file_exists('images/' . $item->photo)): ?>
                                                                    <div style="margin-top:5px;   ">


                                                                        <center>
                                                                            <a href="<?php echo e(asset($item->photo ? 'images/' . $item->photo : 'images/no-image.png')); ?>"
                                                                                target="_blank">
                                                                                <img src="<?php echo e(asset($item->photo ? 'images/' . $item->photo : 'images/no-image.png')); ?>"
                                                                                    alt="Product Image"
                                                                                    style="width: 60px; height: 60px; object-fit: cover; border:1px solid #ccc;">
                                                                            </a>
                                                                        </center>

                                                                    </div>
                                                                <?php endif; ?>
                                                            </td>

                                                            <td><button type="button"
                                                                    class="btn btn-danger btn-sm removeRow">
                                                                    <i class="bx bxs-trash"></i></button></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>


                                        </div>
                                        <button type="button" class="btn btn-primary   mt-4"
                                            id="addRow"><?php echo __('admin.Add Product'); ?></button>
                                    </div>

                                    
                                    <div class="d-flex justify-content-end gap-3 mt-4">
                                        <button type="submit" class="btn btn-success"><?php echo __('admin.Save'); ?></button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
<?php $__env->startSection('footer'); ?>
    <script>
        $(document).ready(function() {
            // إضافة صف جديد
            $('#addRow').click(function() {
                var $tableBody = $('#productItemsTable tbody');
                var $newRow = `<tr>
            <td><input type="text" name="sizes[]" class="form-control"></td>
            <td><input type="text" name="colors[]" value="ابيض" class="form-control" required></td>
            <td><input type="number" min="0" value="0" name="prices[]" class="form-control" required></td>
            <td><input type="number" min="0" value="0" name="sell_prices[]" class="form-control" required></td>
            <td><input type="number" min="0" value="0" name="sell_prices2[]" class="form-control" required></td>
            <td><input type="number" min="1" value="1" name="qtys[]" class="form-control" required></td>
            <td><input type="number" min="1" value="1" name="min_qtys[]" class="form-control" required></td>
            <td><input type="file" name="photo[]" class="form-control" accept="image/*"></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow">   <i class="bx bxs-trash"></i></button></td>
        </tr>`;
                $tableBody.append($newRow);
            });

            // حذف صف
            $('#productItemsTable').on('click', '.removeRow', function() {
                var $tableBody = $(this).closest('tbody');
                if ($tableBody.find('tr').length > 1) {
                    $(this).closest('tr').remove();
                } else {
                    alert('لا يمكن حذف الصف الأخير');
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\laravel_test\pos\resources\views/admin/products/edit.blade.php ENDPATH**/ ?>