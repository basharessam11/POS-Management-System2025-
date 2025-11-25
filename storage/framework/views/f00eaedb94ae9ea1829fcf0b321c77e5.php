<?php $__env->startSection('page', __('admin.Supplier Payments')); ?>


<?php $__env->startSection('contant'); ?>




    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Order List Widget -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                       
                                    <h3 class="mb-2">EGP
                                        <?php echo e(number_format($supplier->total ?? App\Models\Supplier::sum('total'), 2)); ?>

                                    </h3>

                                    <p class="mb-0"><?php echo e(__('admin.Total Debt')); ?></p>
                                </div>
                                <div class="avatar me-sm-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="bx bx-calendar bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>





                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                <div>
                                    <h3 class="mb-2"><?php echo e(number_format($totalReceive, 2)); ?></h3>
                                    <p class="mb-0"><?php echo e(__('admin.Total Received Payments')); ?></p>
                                </div>
                                <div class="avatar me-sm-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="bx bx-wallet bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>



                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                                <div>
                                    <h3 class="mb-2"><?php echo e(number_format($totalPay, 2)); ?></h3>
                                    <p class="mb-0"><?php echo e(__('admin.Total Spent Payments')); ?></p>
                                </div>
                                <div class="avatar me-lg-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="bx bx-check-double bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>




                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start   pb-3 pb-sm-0 card-widget-3">
                                <div>
                                    <h3 class="mb-2"> <?php echo e(number_format($netBalance, 2)); ?></h3>
                                    <p class="mb-0"><?php echo e(__('admin.Net Payment Balance')); ?></p>
                                </div>
                                <div class="avatar me-sm-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="bx bx-wallet bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        



                    </div>
                </div>
            </div>

            <!-- Product List Table -->
            <div class="card">
                <div class="card-header">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title"> <?php echo e(__('admin.Payment Invoices')); ?></h5>


                    </div>

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

                <!-- suppliers List Table -->
                <div class="card">

                    <div class="card-datatable table-responsive">
                        <div id="products-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="card-header d-flex border-top rounded-0 flex-wrap py-md-0">

                                <form method="GET" action="<?php echo e(route('supplier_payments.index')); ?>">
                                    <div class="row g-2 mb-4">
                                        <input type="hidden" name="supplier_id" value="<?php echo e($supplier->id ?? 0); ?>">

                                        <!-- البحث -->
                                        <div class="col-12 col-md-3 d-flex align-items-end">
                                            <input type="search" name="search" value="<?php echo e(request('search')); ?>"
                                                class="form-control" placeholder="<?php echo e(__('admin.Search...')); ?>">
                                        </div>

                                        <!-- نوع الفاتورة -->
                                        <div class="col-12 col-md-2">
                                            <label for="type" class="form-label"><?php echo e(__('admin.Invoice Type')); ?></label>
                                            <select name="type" id="type" class="form-control">
                                                <option value=""><?php echo e(__('admin.All')); ?></option>
                                                <option value="receive"
                                                    <?php echo e(request('type') == 'receive' ? 'selected' : ''); ?>>
                                                    <?php echo e(__('admin.Received')); ?></option>
                                                <option value="pay" <?php echo e(request('type') == 'pay' ? 'selected' : ''); ?>>
                                                    <?php echo e(__('admin.Spent')); ?></option>
                                            </select>
                                        </div>

                                        <!-- التاريخ من -->
                                        <div class="col-12 col-md-3">
                                            <label for="date_from" class="form-label"><?php echo e(__('admin.From Date')); ?></label>
                                            <input type="date" name="date_from" id="date_from"
                                                value="<?php echo e(request('date_from')); ?>" class="form-control">
                                        </div>

                                        <!-- التاريخ إلى -->
                                        <div class="col-12 col-md-3">
                                            <label for="date_to" class="form-label"><?php echo e(__('admin.To Date')); ?></label>
                                            <input type="date" name="date_to" id="date_to"
                                                value="<?php echo e(request('date_to')); ?>" class="form-control">
                                        </div>

                                        <!-- زر البحث -->
                                        <div class="col-12 col-md-1 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary w-100 mt-3">
                                                <?php echo __('admin.Submit'); ?>

                                            </button>
                                        </div>
                                    </div>
                                </form>



                                







                                



                                <div class="d-flex justify-content-start justify-content-md-end align-items-baseline">
                                    <div
                                        class="dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0">

                                        <div class="dt-buttons btn-group flex-wrap"> <button
                                                class="btn btn-secondary add-new btn-danger de me-3" tabindex="0"
                                                aria-controls="products-table" type="button" data-bs-toggle="modal"
                                                data-bs-target="#basicModal2" style="display:none"><span><i
                                                        class="bx bx-trash"></i><span
                                                        class="d-none d-sm-inline-block"><?php echo e(__('admin.Delete')); ?>

                                                    </span></span></button>

                                            <a
                                                href="<?php echo e(route('supplier_payments.create')); ?>?supplier_id=<?php echo e($supplier->id ?? 0); ?>">

                                                <button class="btn btn-secondary add-new btn-primary ms-2" tabindex="0"
                                                    aria-controls="products-table" type="button"
                                                    data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasEcommerceCategoryList"><span><i
                                                            class="bx bx-plus me-0 me-sm-1"></i><?php echo e(__('admin.Add Payment Invoice')); ?></span></button>

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
                                    <th><?php echo e(__('admin.Type')); ?></th>
                                    <th><?php echo e(__('admin.Amount')); ?></th>
                                    <th><?php echo e(__('admin.Note')); ?></th>
                                    <th><?php echo e(__('admin.Created By')); ?></th>
                                    <th><?php echo e(__('admin.Date')); ?></th>

                                    <th><?php echo e(__('admin.Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                

                                <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="odd">

                                        <td>
                                            <input type="checkbox" value="<?php echo e($payment->id); ?>"
                                                onclick="data('dt-checkboxes')" class="dt-checkboxes form-check-input">
                                        </td>

                                        <td>
                                            <input type="checkbox" value="<?php echo e($payment->id); ?>"
                                                onclick="data('dt-checkboxes')" class="dt-checkboxes form-check-input">
                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($payment->type == 'receive'): ?>
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    <?php echo e(__('admin.Received')); ?>

                                                </span>
                                            <?php else: ?>
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    <?php echo e(__('admin.Spent')); ?>

                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                            <?php echo e(number_format($payment->amount, 2)); ?></td>

                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                            <?php echo e($payment->note ?? '---'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($payment->creator->name ?? '---'); ?></td>

                                        <td><?php echo e($payment->created_at->format('Y/m/d h:i A')); ?></td>


                                        <td>
                                            <div class="d-inline-block text-nowrap">
                                                <a href="<?php echo e(route('supplier_payments.edit', $payment->id)); ?>">
                                                    <button class="btn btn-sm btn-icon" title="<?php echo e(__('admin.Edit')); ?>">
                                                        <i class="bx bxs-edit"></i>
                                                    </button>
                                                </a>


                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <div class="row mx-2">
                            <?php echo e($payments->links('vendor.pagination.bootstrap-5')); ?>

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
                            <form method="POST" action="<?php echo e(route('supplier_payments.destroy', 0)); ?>">
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
                            data-i18n="<?php echo e(__('admin.Close')); ?>"><?php echo e(__('admin.Close')); ?></button>
                        <button type="submit" class="btn btn-danger"
                            data-i18n="<?php echo e(__('admin.Delete')); ?>"><?php echo e(__('admin.Delete')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        



    <?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\laravel_test\pos\resources\views/admin/supplier_payment/index.blade.php ENDPATH**/ ?>