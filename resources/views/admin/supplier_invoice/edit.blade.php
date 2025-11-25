@extends('admin.layout.app')

@section('page', __('admin.supplierInvoice_Management'))

@section('contant')

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">

                        <div class="card-header">
                            <h5 class="card-title m-0">{!! __('admin.Edit Supplier Invoice') !!}</h5>
                        </div>

                        <div class="card-body">

                            {{-- Alerts --}}
                            @if (session('success'))
                                <div class="alert alert-success text-center">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger text-center">{{ session('error') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- FORM --}}
                            <form action="{{ route('supplier_invoice.update', $supplierInvoice->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3 g-3">

                          
                                    {{-- Supplier --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{{ __('admin.Suppliers') }}</label>
                                        {{-- The select is disabled/readonly as the supplier usually cannot be changed after creation --}}
                                        <select name="supplier_id" class="form-control select2" disabled>
                                            <option value="{{ $supplierInvoice->supplier->id }}" selected>
                                                {{ $supplierInvoice->supplier->name }}
                                            </option>
                                        </select>
                                        {{-- Use a hidden input to ensure the ID is still submitted --}}
                                        <input type="hidden" name="supplier_id"
                                            value="{{ $supplierInvoice->supplier->id }}">
                                    </div>

                                    {{-- Date --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{{ __('admin.Date') }}</label>
                                        <input type="date" class="form-control"
                                            value="{{ old('date', $supplierInvoice->date) }}" name="date" required>
                                    </div>

                                    {{-- ITEMS TABLE --}}
                                    <div class="col-12 mt-4">
                                        <h6>{{ __('admin.Products') }}</h6>

                                        <div class="table-responsive">
                                            <div id="reader" style="width:100%; max-width:350px; margin:auto;"></div>
                                            <table class="table table-bordered" id="itemsTable">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div style="display: flex; gap: 4px; align-items: center;">
                                                                {{ __('admin.Barcode') }}
                                                                <button type="button"
                                                                    class="btn btn-sm btn-secondary camera-btn  ms-2">
                                                                    <i class="bx bx-camera"></i>
                                                                </button>
                                                            </div>
                                                        </th>
                                                        <th style="min-width: 300px;">{{ __('admin.Product') }}</th>
                                                        <th style="min-width: 100px;">{{ __('admin.Quantity') }}</th>
                                                        <th style="min-width: 120px;">{{ __('admin.Price') }}</th>
                                                        <th style="min-width: 120px;">{{ __('admin.Total') }}</th>
                                                        <th style="min-width: 80px;">{{ __('admin.Delete') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($supplierInvoice->items as $item)
                                                        <tr>
                                                            <td>
                                                                <input type="text" class="form-control barcode"
                                                                    value="{{ $item->productItem->barcode ?? '' }}"
                                                                    placeholder="{{ __('admin.Scan Barcode') }}">
                                                            </td>

                                                            <td>
                                                                <select name="product_item_id[]"
                                                                    class="form-control select2 productSelect">

                                                                    <option value="{{ $item->product_item_id }}" selected
                                                                        selected="" data-price="{{ $item->price }}"
                                                                        data-price2="{{ $item->price }}"
                                                                        data-stock="{{ $item->productItem->qty }}"
                                                                        data-barcode="{{ $item->productItem->barcode }}"
                                                                        data-select2-id="{{ $item->product_item_id }}">
                                                                        {{ $item->productItem->product->name ?? __('admin.No Name') }}
                                                                    </option>


                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input type="number" name="qty[]"
                                                                    class="form-control qty" min="1"
                                                                    value="{{ $item->qty }}" required>
                                                            </td>

                                                            <td>
                                                                <input type="number" name="price[]"
                                                                    class="form-control price" step="0.01"
                                                                    value="{{ $item->price }}" required>
                                                            </td>

                                                            <td>
                                                                <input type="number" class="form-control rowTotal" readonly
                                                                    value="{{ $item->qty * $item->price }}">
                                                            </td>

                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm removeRow">
                                                                    <i class="bx bxs-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>

                                        <button type="button" class="btn btn-primary mt-3" id="addRow">
                                            <i class='bx bx-plus'></i> {{ __('admin.Add Product') }}
                                        </button>

                                    </div>

                                    {{-- TOTALS --}}
                                    <div class="col-12 col-md-6 mt-3">
                                        <label class="form-label">{{ __('admin.Total') }}</label>
                                        {{-- Total (sum of rowTotal) --}}
                                        <input type="number" class="form-control" id="total" name="total" readonly
                                            value="{{ old('total', $supplierInvoice->total) }}">
                                    </div>


                                    <div class="col-12 col-md-6 mt-3" style="display: none">
                                        <label class="form-label">{{ __('admin.Net') }}</label>
                                        {{-- Net (total - discount) --}}
                                        <input type="number" class="form-control net" id="net" name="net"
                                            readonly value="{{ old('net', $supplierInvoice->net) }}">
                                    </div>

                                    <div class="col-12 col-md-6 mt-3">
                                        <label class="form-label">{{ __('admin.Paid') }}</label>
                                        {{-- Paid Amount --}}
                                        <input type="number" class="form-control" id="paid" name="paid"
                                            value="{{ old('paid', $supplierInvoice->paid) }}">
                                    </div>

                                    <div class="col-12 col-md-6 mt-3">
                                        <label class="form-label">{{ __('admin.Remaining') }}</label>
                                        {{-- Remaining (net - paid) --}}
                                        <input type="number" class="form-control" id="remaining" name="remaining"
                                            readonly value="{{ old('remaining', $supplierInvoice->remaining) }}">
                                    </div>

                                    <div class="col-12 col-md-6 mt-3">
                                        <label class="form-label">{{ __('admin.Previous Balance') }}</label>
                                        {{-- Remaining (net - paid) --}}
                                        <input type="number" class="form-control" readonly
                                            value="{{ old('remaining', $supplierInvoice->supplier->total) }}">
                                    </div>

                                    <div class="d-flex justify-content-end gap-3 mt-4">
                                        <button type="submit" name="save" value="save"
                                            class="btn btn-success">{{ __('admin.Save Invoice') }}</button>
                                        <button type="submit" name="save" value="print3"
                                            class="btn btn-primary">{{ __('admin.Save and Print') }}</button>
                                    </div>

                                </div>

                            </form>

                        </div> {{-- card-body --}}

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('footer')
    <script>
        $(document).ready(function() {
            $('form').on('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // منع الإرسال
                    return false;
                }
            });

            // --- دوال الحسابات العامة ---

            // حساب الصف والإجمالي العام للفاتورة
            function calcRowTotal(tr) {
                let qty = parseFloat(tr.find('.qty').val()) || 0;
                let price = parseFloat(tr.find('.price').val()) || 0;
                tr.find('.rowTotal').val(qty * price);
                calcTotal(); // استدعاء دالة الإجمالي العام بعد تحديث أي صف
            }

            // حساب إجمالي المرتجع للصف الواحد
            function calcReturnRowTotal(tr) {
                let qty = parseFloat(tr.find('.returnQty').val()) || 0;
                let price = parseFloat(tr.find('.returnPrice').val()) || 0;
                tr.find('.returnTotal').val(qty * price);
                calcReturnsTotal(); // استدعاء دالة إجمالي المرتجعات
            }

            // حساب الإجمالي الكلي للمرتجعات
            function calcReturnsTotal() {
                let totalReturns = 0;
                $('#returnsTable .returnTotal').each(function() { // تحديد نطاق البحث بـ #returnsTable
                    totalReturns += parseFloat($(this).val()) || 0;
                });
                $('#returnsTotal').val(totalReturns);
                calcTotal(); // تحديث الإجمالي الكلي للفاتورة بعد حساب المرتجع
            }

            function calcTotal() {
                let total = 0;
                $('#itemsTable .rowTotal').each(function() { // تحديد نطاق البحث بـ #itemsTable
                    total += parseFloat($(this).val()) || 0;
                });
                let discount = parseFloat($('#discount').val()) || 0;

                // حساب إجمالي المرتجعات من حقل returnsTotal
                let returnsTotal = parseFloat($('#returnsTotal').val()) || 0;

                // الصافي = الإجمالي - الخصم - إجمالي المرتجعات
                let net = total - discount - returnsTotal;

                $('#total').val(total);
                $('#net').val(net);

                let paid = parseFloat($('#paid').val()) || 0;
               

                $('#remaining').val(net - paid);
                $('#net').data('prevNet', net);
            }

            // ربط الحقول المالية بالإجمالي العام
            $('#discount, #paid').on('input', calcTotal);
            // calcReturnsTotal يتم استدعاؤها من خلال calcReturnRowTotal

            // --- إدارة صفوف الفاتورة (ItemsTable) ---

            // اضافة صف جديد (منتج)
            function addRow() {
                let tr = `<tr>
            <td><input type="text" class="form-control barcode" placeholder="{{ __('admin.Scan Barcode') }}"></td>
            <td><select name="product_item_id[]" class="form-control select2 productSelect"><option value="">{{ __('admin.Select Product') }}</option></select></td>
            <td><input type="number" name="qty[]" class="form-control qty" min="1" value="1"></td>
            <td><input type="number" name="price[]" class="form-control price" min="0" value="0" readonly></td>
            <td><input type="number" class="form-control rowTotal" readonly value="0"></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="bx bxs-trash"></i></button></td>
        </tr>`;
                $('#itemsTable tbody').append(tr);
                initSelect2($('#itemsTable tbody tr:last .productSelect'));
            }

            $('#addRow').click(addRow);

            $('#itemsTable').on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
                calcTotal();
            });

            // ربط حقول الكمية والسعر في جدول الفاتورة (ItemsTable)
            $('#itemsTable').on('input', '.qty, .price', function() {
                calcRowTotal($(this).closest('tr'));
            });


            // --- إدارة صفوف المرتجعات (ReturnsTable) ---

            // اضافة صف جديد (مرتجع)
            function addReturnRow() {
                let tr = `<tr>
            <td><input type="text" class="form-control barcode returnBarcode" placeholder="{{ __('admin.Scan Barcode') }}"></td> <td><select name="return_product_item_id[]" class="form-control select2 returnProductSelect"><option value="">{{ __('admin.Select Product') }}</option></select></td>
            <td><input type="number" name="return_qty[]" class="form-control returnQty" min="1" value="1"></td>
            <td><input type="number" name="return_price[]" class="form-control returnPrice" min="0" value="0"></td>
            <td><input type="number" class="form-control returnTotal" readonly value="0"></td>
            <td><button type="button" class="btn btn-danger btn-sm removeReturnRow"><i class="bx bxs-trash"></i></button></td>
        </tr>`;
                $('#returnsTable tbody').append(tr);
                initSelect2($('#returnsTable tbody tr:last .returnProductSelect')); // تهيئة Select2 للمرتجع
            }

            $('#addReturnRow').click(addReturnRow);

            $('#returnsTable').on('click', '.removeReturnRow', function() {
                $(this).closest('tr').remove();
                calcReturnsTotal();
            });

            // ربط حقول الكمية والسعر في جدول المرتجعات (ReturnsTable)
            $('#returnsTable').on('input', '.returnQty, .returnPrice', function() {
                calcReturnRowTotal($(this).closest('tr'));
            });


            // --- تهيئة Select2 والمنتجات ---

            // تهيئة Select2 العامة (تستخدم لجدول الفاتورة وجدول المرتجعات)
            function initSelect2(select) {
                select.select2({
                    placeholder: '{{ __('admin.Search for product...') }}',
                    ajax: {
                        url: '{{ route('invoice.search1') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.results
                            };
                        }
                    }
                });
            }

            // دالة لتحديث سعر المرتجع عند اختيار المنتج أو تغيير نوع الفاتورة
            function updateReturnPrice(tr) {
                let selectedOption = tr.find('.returnProductSelect option:selected');
                if (!selectedOption.length) return;

                let type = $('#invoiceType').val();
                // استخدم سعر القطاعي (price) أو سعر الجملة (price2)
                let price = type == 1 ? selectedOption.data('price') || 0 : selectedOption.data('price2') || 0;

                tr.find('.returnPrice').val(price);

                // ضبط الكمية الافتراضية إذا كانت صفر
                let qtyInput = tr.find('.returnQty');
                if (!qtyInput.val() || parseFloat(qtyInput.val()) <= 0) {
                    qtyInput.val(1);
                }

                calcReturnRowTotal(tr);
            }

            // دالة لتحديث السعر والكمية للصف (من الكود الأصلي)
            function updatePriceAndQty(tr) {
                let selectedOption = tr.find('.productSelect option:selected');
                if (!selectedOption.length) return;

                let type = $('#invoiceType').val();
                let price = type == 1 ? selectedOption.data('price') || 0 : selectedOption.data('price2') || 0;
                tr.find('.price').val(price);

                let qtyInput = tr.find('.qty');
                if (!qtyInput.val() || parseFloat(qtyInput.val()) <= 0) {
                    qtyInput.val(1);
                }

                calcRowTotal(tr);
            }

            // عند اختيار منتج من Select2 في جدول الفاتورة
            $('#itemsTable').on('select2:select', '.productSelect', function(e) {
                let tr = $(this).closest('tr');
                let data = e.params.data;
                let stock = parseFloat(data.stock) || 0;

                if (stock <= 0) {
                    alert('⚠ {{ __('admin.Product is currently unavailable') }}');
                    $(this).val(null).trigger('change');
                    return;
                }

                let found = false;
                $('#itemsTable tbody tr').each(function() {
                    if ($(this)[0] !== tr[0]) {
                        let existing = $(this).find('.productSelect').val();
                        if (existing == data.id) {
                            let qtyInput = $(this).find('.qty');
                            let newQty = parseFloat(qtyInput.val()) + 1;
                            if (newQty > stock) newQty = stock;
                            qtyInput.val(newQty);
                            calcRowTotal($(this));
                            tr.find('.productSelect').val(null).trigger('change');
                            tr.find('.barcode').val('');
                            found = true;
                            return false;
                        }
                    }
                });

                if (found) return;

                let newOption = new Option(data.text, data.id, true, true);
                $(newOption)
                    .attr('data-price', data.price)
                    .attr('data-price2', data.price2)
                    .attr('data-stock', data.stock)
                    .attr('data-barcode', data.barcode || '');

                tr.find('.productSelect').append(newOption).trigger('change');
                tr.find('.barcode').val(data.barcode || '');
                tr.find('.qty').val(1);
                updatePriceAndQty(tr);

                let lastRow = $('#itemsTable tbody tr:last');
                if (lastRow.find('.productSelect').val()) {
                    addRow();
                    $('#itemsTable tbody tr:last .barcode').focus();
                }
            });

            // عند اختيار منتج من Select2 في جدول المرتجعات
            $('#returnsTable').on('select2:select', '.returnProductSelect', function(e) {
                let tr = $(this).closest('tr');
                let data = e.params.data;
                let newOption = new Option(data.text, data.id, true, true);

                // لإضافة البيانات الخاصة بالسعر (price, price2) والباركود إلى الـ option
                $(newOption)
                    .attr('data-price', data.price)
                    .attr('data-price2', data.price2)
                    .attr('data-barcode', data.barcode || '');

                tr.find('.returnProductSelect').append(newOption).trigger('change');

                // تحديث سعر المرتجع بناءً على نوع الفاتورة
                updateReturnPrice(tr);

                // تحديث حقل الباركود
                tr.find('.returnBarcode').val(data.barcode || '');


                // إضافة صف مرتجع جديد إذا كان الصف الأخير مليئًا
                let lastReturnRow = $('#returnsTable tbody tr:last');
                if (lastReturnRow.find('.returnProductSelect').val()) {
                    addReturnRow();
                    $('#returnsTable tbody tr:last .returnBarcode').focus();
                }
            });


            // --- قراءة الباركود الموحدة (للفاتورة والمرتجع) ---

            $('#itemsTable, #returnsTable').on('input', '.barcode', function() {
                let val = $(this).val().trim();
                if (val.length !== 6) return; // أول ما يكون 6 أرقام فقط يكمل

                let tr = $(this).closest('tr');
                let isReturnTable = tr.closest('#returnsTable').length > 0;

                let selectClass = isReturnTable ? '.returnProductSelect' : '.productSelect';
                let qtyClass = isReturnTable ? '.returnQty' : '.qty';
                let calcFn = isReturnTable ? calcReturnRowTotal : calcRowTotal;
                let updateFn = isReturnTable ? updateReturnPrice : updatePriceAndQty;
                let addFn = isReturnTable ? addReturnRow : addRow;
                let targetTable = isReturnTable ? '#returnsTable' : '#itemsTable';

                // منع البحث المتكرر إذا كان الباركود الحالي مطابقًا للباركود في Select2
                let currentBarcode = tr.find(selectClass).find('option:selected').data('barcode');
                if (currentBarcode && currentBarcode == val) return;

                $.ajax({
                    url: '{{ route('invoice.search1') }}',
                    data: {
                        q: val
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.results.length === 0) {
                            alert('{{ __('admin.Product not found') }}');
                            tr.find('.barcode').val('');
                            tr.find(selectClass).val(null).trigger('change');
                            return;
                        }

                        let item = data.results[0];
                        let stock = parseFloat(item.stock) || 0;

                        if (!isReturnTable && stock <= 0) {
                            alert('⚠ {{ __('admin.Product is currently unavailable') }}');
                            tr.find('.barcode').val('');
                            return;
                        }

                        let found = false;

                        $(targetTable + ' tbody tr').each(function() {
                            let currentTr = $(this);
                            let existingId = currentTr.find(selectClass).val();
                            if (existingId == item.id) {
                                let qtyInput = currentTr.find(qtyClass);
                                let newQty = parseFloat(qtyInput.val()) + 1;
                                if (!isReturnTable && newQty > stock) newQty = stock;
                                qtyInput.val(newQty);
                                calcFn(currentTr);

                                tr.find('.barcode').val('');
                                tr.find(selectClass).val(null).trigger('change');
                                found = true;
                                return false;
                            }
                        });

                        if (found) return;

                        let newOption = new Option(item.text, item.id, true, true);
                        $(newOption)
                            .attr('data-price', item.price)
                            .attr('data-price2', item.price2)
                            .attr('data-stock', item.stock)
                            .attr('data-barcode', item.barcode);

                        tr.find(selectClass).append(newOption).trigger('change');
                        tr.find('.barcode').val(item.barcode);
                        tr.find(qtyClass).val(1);

                        updateFn(tr);

                        let lastRow = $(targetTable + ' tbody tr:last');
                        if (lastRow.find(selectClass).val()) {
                            addFn();
                            $(targetTable + ' tbody tr:last .barcode').focus();
                        }
                    }
                });
            });



            // التحقق من الكمية يدوياً في جدول الفاتورة (الكود الأصلي)
            $('#itemsTable').on('input', '.qty', function() {
                let tr = $(this).closest('tr');
                let stock = parseFloat(tr.find('.productSelect option:selected').data('stock')) || 0;
                let qty = parseFloat($(this).val()) || 0;
                if (qty > stock) {
                    $(this).val(stock);
                    alert('⚠ {{ __('admin.The requested quantity is greater than the available quantity:') }} ' +
                        stock);
                }
                if (qty < 1) $(this).val(1);
                calcRowTotal(tr);
            });

            // عند تغيير نوع الفاتورة (لتحديث الأسعار في كلا الجدولين)
            $('#invoiceType').on('change', function() {
                // تحديث أسعار منتجات الفاتورة
                $('#itemsTable tbody tr').each(function() {
                    updatePriceAndQty($(this));
                });

                // تحديث أسعار منتجات المرتجعات
                $('#returnsTable tbody tr').each(function() {
                    updateReturnPrice($(this));
                });
            });


            // --- تهيئة عند التحميل ---

            // تهيئة Select2 للصفوف الموجودة مسبقًا
            $('.productSelect').each(function() {
                initSelect2($(this));
            });
            $('.returnProductSelect').each(function() {
                initSelect2($(this));
            });

            // إضافة صف فارغ في بداية التحميل إذا كان جدول المرتجعات فارغًا
            if ($('#returnsTable tbody tr').length === 0) {
                addReturnRow();
            }

            // حساب الإجماليات الأولية
            calcTotal();


        });
    </script>
    <script>
        $(document).ready(function() {

            // ==================== جدول الفاتورة ====================
            let html5QrInvoice = null;
            let isRunningInvoice = false;
            let pauseScanInvoice = false;

            function onScanSuccessInvoice(decodedText) {
                if (pauseScanInvoice) return;
                pauseScanInvoice = true;

                alert("{{ __('admin.Barcode scanned:') }} " + decodedText);

                // البحث عن أول صف فارغ
                let tr = $('#itemsTable tbody tr').filter(function() {
                    return !$(this).find('.productSelect').val();
                }).first();

                if (!tr.length) {
                    $('#addRow').click();
                    tr = $('#itemsTable tbody tr:last');
                }

                tr.find('.barcode').val(decodedText).trigger('input');
                pauseScanInvoice = false;
            }

            $(document).on('click', '.camera-btn', async function(e) {
                e.preventDefault();
                let btn = $(this);

                if (!html5QrInvoice) {
                    html5QrInvoice = new Html5Qrcode("reader");
                }

                if (!isRunningInvoice) {
                    const devices = await Html5Qrcode.getCameras();
                    if (!devices || devices.length === 0) {
                        alert("{{ __('admin.No camera found') }}");
                        return;
                    }

                    let backCam = devices.find(cam =>
                        cam.label.toLowerCase().includes("back") ||
                        cam.label.toLowerCase().includes("environment")
                    );
                    let camId = backCam ? backCam.id : devices[0].id;

                    html5QrInvoice.start({ facingMode: "environment" }, {
                            fps: 10,
                            qrbox: 250
                        }, onScanSuccessInvoice)
                        .then(() => {
                            isRunningInvoice = true;
                            btn.html('<i class="bx bx-stop-circle"></i>');
                        })
                        .catch(err => {
                            console.error(err);
                            alert("{{ __('admin.Failed to start camera:') }} " + err);
                        });

                } else {
                    html5QrInvoice.stop().then(() => {
                        isRunningInvoice = false;
                        btn.html('<i class="bx bx-camera"></i>');
                    }).catch(err => {
                        console.error(err);
                        alert("{{ __('admin.Failed to stop camera:') }} " + err);
                    });
                }
            });

            // ==================== جدول المرتجعات ====================
            let html5QrReturns = null;
            let isRunningReturns = false;
            let pauseScanReturns = false;

            function onScanSuccessReturns(decodedText) {
                if (pauseScanReturns) return;
                pauseScanReturns = true;

                alert("{{ __('admin.Barcode scanned:') }} " + decodedText);

                // البحث عن أول صف فارغ في جدول المرتجعات
                let tr = $('#returnsTable tbody tr').filter(function() {
                    return !$(this).find('.returnProductSelect').val();
                }).first();

                if (!tr.length) {
                    $('#addReturnRow').click();
                    tr = $('#returnsTable tbody tr:last');
                }
                tr.find('.returnBarcode').val(decodedText).trigger('input');

                pauseScanReturns = false;
            }

            $(document).on('click', '.camera-btn1', async function(e) {
                e.preventDefault();
                let btn = $(this);

                if (!html5QrReturns) {
                    html5QrReturns = new Html5Qrcode("reader1");
                }

                if (!isRunningReturns) {
                    const devices = await Html5Qrcode.getCameras();
                    if (!devices || devices.length === 0) {
                        alert("{{ __('admin.No camera found') }}");
                        return;
                    }

                    let backCam = devices.find(cam =>
                        cam.label.toLowerCase().includes("back") ||
                        cam.label.toLowerCase().includes("environment")
                    );
                    let camId = backCam ? backCam.id : devices[0].id;

                    html5QrReturns.start({ facingMode: "environment" }, {
                            fps: 10,
                            qrbox: 250
                        }, onScanSuccessReturns)
                        .then(() => {
                            isRunningReturns = true;
                            btn.html('<i class="bx bx-stop-circle"></i>');
                        })
                        .catch(err => {
                            console.error(err);
                            alert("{{ __('admin.Failed to start camera:') }} " + err);
                        });

                } else {
                    html5QrReturns.stop().then(() => {
                        isRunningReturns = false;
                        btn.html('<i class="bx bx-camera"></i>');
                    }).catch(err => {
                        console.error(err);
                        alert("{{ __('admin.Failed to stop camera:') }} " + err);
                    });
                }
            });

        });
    </script>
@endsection
