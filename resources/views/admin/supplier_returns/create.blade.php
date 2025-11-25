@extends('admin.layout.app')

@section('page', __('admin.Invoice_Management'))

@section('contant')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">


                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">{!! __('admin.Add Returns') !!}</h5>
                            <button type="button" class="btn btn-label-secondary" id="clearCache">
                                {!! __('admin.Clear Form') !!}
                            </button>

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
                            <form action="{{ route('supplier_returns.store') }}" method="POST">
                                @csrf

                                <div class="row mb-3 g-3">


                                    {{-- Supplier --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{{ __('admin.Supplier') }}</label>
                                        <select name="supplier_id" class="form-control select2" id="customerSelect" required
                                            readonly>
                                            <option value="">{!! __('admin.select') !!}</option>
                                            @foreach ($suppliers as $supplier)
                                                <option {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}
                                                    value="{{ $supplier->id }}" data-balance="{{ $supplier->total }}">
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    {{-- Date --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Date') !!}</label>
                                        <input type="date" class="form-control" name="date"
                                            value="{{ old('date', date('Y-m-d')) }}" required>
                                    </div>




                                    {{-- Returns Table --}}
                                    <div class="col-12 mt-4">
                                        <h6>{{ __('admin.Products') }}</h6>

                                        <div class="table-responsive">
                                            <div id="reader1" style="width:100%; max-width:350px; margin:auto;">
                                            </div>
                                            <table class="table table-bordered" id="returnsTable">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div style="display: flex; gap: 4px; align-items: center;">
                                                                {{ __('admin.Barcode') }}
                                                                <button type="button"
                                                                    class="btn btn-sm btn-secondary camera-btn1  ms-2">
                                                                    <i class="bx bx-camera"></i>
                                                                </button>
                                                            </div>
                                                        </th>
                                                        <th style="min-width: 300px;">{{ __('admin.Product') }}</th>
                                                        <th style="min-width: 80px;">{{ __('admin.Quantity') }}</th>
                                                        <th style="min-width: 80px;">{{ __('admin.Price') }}</th>
                                                        <th style="min-width: 100px;">{{ __('admin.Total') }}</th>
                                                        <th style="min-width: 80px;">{{ __('admin.Delete') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>

                                        <button type="button" class="btn btn-warning mt-3"
                                            id="addReturnRow">{{ __('admin.Add Return') }}</button>


                                    </div>

                                    <div class="col-12 col-md-6 mt-3">
                                        <label class="form-label">{{ __('admin.Total Returns') }}</label>
                                        <input type="number" class="form-control" id="returnsTotal" name="returns_total"
                                            readonly value="0">
                                    </div>
                                    <div class="col-12 col-md-6 mt-3">
                                        <label class="form-label">{{ __('admin.Net') }}</label>
                                        <input type="number" class="form-control net" id="net" name="net"
                                            readonly value="0">
                                    </div>
                                    <div class="col-12 col-md-6 mt-3">
                                        <label class="form-label">{{ __('admin.Paid') }}</label>
                                        <input type="number" class="form-control" id="paid" name="paid"
                                            value="0">
                                    </div>

                                    <div class="col-12 col-md-6 mt-3">
                                        <label class="form-label">{{ __('admin.Previous Balance') }}</label>
                                        <input type="number" class="form-control" id="price" readonly value="0">
                                    </div>

                                    <style>
                                        /* تحسين الجدول للموبايل */
                                        @media (max-width: 576px) {

                                            #returnsTable th,
                                            #returnsTable td {
                                                font-size: 12px;
                                                padding: 4px;
                                            }

                                            .form-control {
                                                font-size: 12px;
                                                padding: 4px 6px;
                                            }

                                            .btn-sm {
                                                padding: 2px 6px;
                                                font-size: 12px;
                                            }
                                        }
                                    </style>

                                    <div class="col-12 mt-3">
                                        <label class="form-label">{{ __('admin.Notes') }}</label>
                                        <textarea name="note" class="form-control" rows="2"></textarea>
                                    </div>

                                    <div class="d-flex justify-content-end gap-3 mt-4">
                                        <button type="submit" name="save" value="save"
                                            class="btn btn-success">{{ __('admin.Save') }}
                                        </button>

                                    </div>

                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('footer')

    <script>
        $(document).ready(function() {

            // --- 1. ثوابت و علامات للتحكم في الحفظ ---
            const CACHE_KEY = 'supplier_returns_form_cache';
            let isInitializing = true; // علامة لمنع الحفظ التلقائي أثناء تحميل البيانات

            // --- 2. دوال Select2 الأساسية ---

            // تهيئة Select2 مع الحل لمشكلة القائمة داخل table-responsive
            function initSelect2(select) {
                // تدمير أي Select2 قديم وإعادة التهيئة
                if (select.data('select2')) {
                    select.select2('destroy');
                }
                select.select2({
                    placeholder: '{{ __('admin.Search for product...') }}',
                    // الحل لمشكلة عدم فتح القائمة وتداخلها مع الجدول (تمت إضافته)
                    dropdownParent: $('body'),
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

            // --- 3. دوال الكاش (Cache) ---

            // دالة الحفظ التلقائي للكاش
            function saveFormData() {
                if (isInitializing) return; // منع الحفظ أثناء التهيئة

                const formData = {};

                // حفظ البيانات الرئيسية
                formData.branch_id = $('[name="branch_id"]').val();
                formData.supplier_id = $('[name="supplier_id"]').val();
                formData.type = $('[name="type"]').val();
                formData.user_id = $('[name="user_id"]').val();
                formData.discount = $('#discount').val();
                formData.paid = $('#paid').val();
                formData.note = $('[name="note"]').val();

                // حفظ بيانات المنتجات (ItemsTable)
                formData.items = [];
                $('#itemsTable tbody tr').each(function() {
                    let tr = $(this);
                    let productSelect = tr.find('.productSelect');

                    if (productSelect.val() && productSelect.val() !== '') {
                        const selectedOption = productSelect.find('option:selected');

                        formData.items.push({
                            product_item_id: productSelect.val(),
                            barcode: tr.find('.barcode').val(),
                            qty: tr.find('.qty').val(),
                            price: tr.find('.price').val(),
                            stock: tr.find('.qty1').val(),

                            // بيانات الـ Option ضرورية لإعادة بناء Select2
                            product_text: selectedOption.text(),
                            data_price: selectedOption.data('price'),
                            data_price2: selectedOption.data('price2'),
                            data_barcode: selectedOption.data('barcode'),
                            data_stock: selectedOption.data('stock'),
                        });
                    }
                });

                // حفظ بيانات المرتجعات (ReturnsTable)
                formData.returns = [];
                $('#returnsTable tbody tr').each(function() {
                    let tr = $(this);
                    let productSelect = tr.find('.returnProductSelect');

                    if (productSelect.val() && productSelect.val() !== '') {
                        const selectedOption = productSelect.find('option:selected');

                        formData.returns.push({
                            return_product_item_id: productSelect.val(),
                            barcode: tr.find('.returnBarcode').val(),
                            return_qty: tr.find('.returnQty').val(),
                            return_price: tr.find('.returnPrice').val(),

                            // بيانات الـ Option ضرورية لإعادة بناء Select2
                            product_text: selectedOption.text(),
                            data_price: selectedOption.data('price'),
                            data_price2: selectedOption.data('price2'),
                            data_barcode: selectedOption.data('barcode'),
                        });
                    }
                });

                localStorage.setItem(CACHE_KEY, JSON.stringify(formData));
            }

            // دالة تحميل البيانات من الكاش
            function loadFormData() {
                const cachedData = localStorage.getItem(CACHE_KEY);
                if (!cachedData) return false;

                const formData = JSON.parse(cachedData);

                // استعادة البيانات الرئيسية
                $('[name="branch_id"]').val(formData.branch_id).trigger('change');
                $('[name="supplier_id"]').val(formData.supplier_id).trigger('change');
                $('[name="type"]').val(formData.type).trigger('change');
                $('[name="user_id"]').val(formData.user_id).trigger('change');
                $('#discount').val(formData.discount || 0);
                $('#paid').val(formData.paid || 0);
                $('[name="note"]').val(formData.note);

                // --- استعادة صفوف المنتجات (ItemsTable) ---
                if (formData.items && formData.items.length > 0) {
                    $('#itemsTable tbody').empty();
                    formData.items.forEach(function(item) {
                        addRow(false); // إضافة الصف بدون تهيئة Select2
                        const tr = $('#itemsTable tbody tr:last');
                        const productSelect = tr.find('.productSelect');

                        // إعادة بناء Option
                        const newOption = new Option(item.product_text, item.product_item_id, true, true);
                        $(newOption)
                            .attr('data-price', item.data_price)
                            .attr('data-price2', item.data_price2)
                            .attr('data-stock', item.data_stock)
                            .attr('data-barcode', item.data_barcode);

                        productSelect.append(newOption);
                        productSelect.val(item.product_item_id);

                        // تعبئة باقي الحقول
                        tr.find('.barcode').val(item.barcode);
                        tr.find('.qty').val(item.qty);
                        tr.find('.price').val(item.price);
                        tr.find('.qty1').val(item.stock);

                        calcRowTotal(tr);
                    });
                }

                // --- استعادة صفوف المرتجعات (ReturnsTable) ---
                if (formData.returns && formData.returns.length > 0) {
                    $('#returnsTable tbody').empty();
                    formData.returns.forEach(function(item) {
                        addReturnRow(false); // إضافة الصف بدون تهيئة Select2
                        const tr = $('#returnsTable tbody tr:last');
                        const productSelect = tr.find('.returnProductSelect');

                        // إعادة بناء Option
                        const newOption = new Option(item.product_text, item.return_product_item_id, true,
                            true);
                        $(newOption)
                            .attr('data-price', item.data_price)
                            .attr('data-price2', item.data_price2)
                            .attr('data-barcode', item.data_barcode);

                        productSelect.append(newOption);
                        productSelect.val(item.return_product_item_id);

                        // تعبئة باقي الحقول
                        tr.find('.returnBarcode').val(item.barcode);
                        tr.find('.returnQty').val(item.return_qty);
                        tr.find('.returnPrice').val(item.return_price);

                        calcReturnRowTotal(tr);
                    });
                }
                return true;
            }

            // دالة مسح الكاش (تعمل فقط بضغط الزر)
            function clearFormData() {
                localStorage.removeItem(CACHE_KEY);
                alert('{{ __('admin.Are you sure you want to delete all saved data and start from scratch?') }}');
                window.location.reload();
            }

            // --- 4. الإعداد الأولي والدمج ---

            // محاولة تحميل البيانات عند بدء التشغيل
            const isCached = loadFormData();

            // إضافة صفوف فارغة وضمان التهيئة
            if (!isCached || $('#itemsTable tbody tr').length === 0 || $('#itemsTable tbody tr:last .productSelect')
                .val()) {
                addRow(true);
            }
            if (!isCached || $('#returnsTable tbody tr').length === 0 || $(
                    '#returnsTable tbody tr:last .returnProductSelect').val()) {
                addReturnRow(true);
            }

            // تهيئة Select2 لجميع العناصر بعد تحميل الكاش
            $('.productSelect, .returnProductSelect').each(function() {
                initSelect2($(this));
            });

            // تحديث رصيد العميل الأولي
            let balance = $('#customerSelect').find('option:selected').data('balance') || 0;
            $('#price').val(balance);
            calcTotal();

            // تفعيل الحفظ التلقائي بعد انتهاء عملية التهيئة
            isInitializing = false;

            // ربط زر مسح الكاش (Clear Cache Button)

            $('#clearCache').on('click', clearFormData);

            // ربط الحفظ التلقائي بجميع حقول الإدخال المهمة
            $('form').on('change input', 'select:not(.select2-hidden-accessible), input:not([readonly]), textarea',
                function() {
                    saveFormData();
                });

            // منع إرسال النموذج بضغط Enter
            $('form').on('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    return false;
                }
            });


            // --- 5. دوال الحسابات والإدارة (الكود الأصلي مع إضافة حفظ الكاش) ---

            function calcRowTotal(tr) {
                let qty = parseFloat(tr.find('.qty').val()) || 0;
                let price = parseFloat(tr.find('.price').val()) || 0;
                tr.find('.rowTotal').val(qty * price);
                calcTotal();
            }

            function calcReturnRowTotal(tr) {
                let qty = parseFloat(tr.find('.returnQty').val()) || 0;
                let price = parseFloat(tr.find('.returnPrice').val()) || 0;
                tr.find('.returnTotal').val(qty * price);
                calcReturnsTotal();
            }

            function calcReturnsTotal() {
                let totalReturns = 0;
                $('#returnsTable .returnTotal').each(function() {
                    totalReturns += parseFloat($(this).val()) || 0;
                });
                $('#returnsTotal').val(totalReturns);
                calcTotal();
            }

            function calcTotal() {
                let total = 0;
                $('#itemsTable .rowTotal').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                let discount = parseFloat($('#discount').val()) || 0;
                let returnsTotal = parseFloat($('#returnsTotal').val()) || 0;
                let net = total - discount - returnsTotal;

                $('#total').val(total);
                $('#net').val(net);

                let paid = parseFloat($('#paid').val()) || 0;
                if (paid === 0 || paid === $('#net').data('prevNet')) {
                    paid = net;
                    $('#paid').val(paid);
                }

                $('#remaining').val(net - paid);
                $('#net').data('prevNet', net);

                saveFormData(); // حفظ الكاش بعد كل عملية حساب
            }

            $('#discount, #paid').on('input', calcTotal);

            $('#customerSelect').on('change', function() {
                let balance = $(this).find('option:selected').data('balance') || 0;
                $('#price').val(balance);
                saveFormData();
            });


            // اضافة صف جديد (منتج)
            function addRow(init = true) {
                let tr = `<tr>
            <td><input type="text" class="form-control barcode" placeholder="{{ __('admin.Scan Barcode') }}"></td>
            <td><select name="product_item_id[]" class="form-control select2 productSelect"><option value="">{{ __('admin.Select Product') }}</option></select></td>
            <td><input type="number" disabled  class="form-control qty1"  value="0"></td>
            <td><input type="number" name="qty[]" class="form-control qty" min="1" value="1"></td>

            <td><input type="number" name="price[]" class="form-control price" min="0" value="0" readonly></td>
            <td><input type="number" class="form-control rowTotal" readonly value="0"></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="bx bxs-trash"></i></button></td>
        </tr>`;
                $('#itemsTable tbody').append(tr);
                if (init) {
                    initSelect2($('#itemsTable tbody tr:last .productSelect'));
                }
            }

            $('#addRow').click(function() {
                addRow(true);
                saveFormData();
            });

            $('#itemsTable').on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
                calcTotal();
                saveFormData();
            });

            $('#itemsTable').on('input', '.qty, .price', function() {
                calcRowTotal($(this).closest('tr'));
                saveFormData();
            });

            // اضافة صف جديد (مرتجع)
            function addReturnRow(init = true) {
                let tr = `<tr>
            <td>
                 <div style="display: flex; gap: 4px; align-items: center;">
                    <input type="text" class="form-control barcode returnBarcode" placeholder="{{ __('admin.Scan Barcode') }}" style="flex: 1;">
                 </div>
            </td>
            <td><select name="return_product_item_id[]" class="form-control select2 returnProductSelect"><option value="">{{ __('admin.Select Product') }}</option></select></td>
            <td><input type="number" name="return_qty[]" class="form-control returnQty" min="1" value="1"></td>
            <td><input type="number" name="return_price[]" class="form-control returnPrice" min="0" value="0"></td>
            <td><input type="number" class="form-control returnTotal" readonly value="0"></td>
            <td><button type="button" class="btn btn-danger btn-sm removeReturnRow"><i class="bx bxs-trash"></i></button></td>
        </tr>`;
                $('#returnsTable tbody').append(tr);
                if (init) {
                    initSelect2($('#returnsTable tbody tr:last .returnProductSelect'));
                }
            }

            $('#addReturnRow').click(function() {
                addReturnRow(true);
                saveFormData();
            });

            $('#returnsTable').on('click', '.removeReturnRow', function() {
                $(this).closest('tr').remove();
                calcReturnsTotal();
                saveFormData();
            });

            $('#returnsTable').on('input', '.returnQty, .returnPrice', function() {
                calcReturnRowTotal($(this).closest('tr'));
                saveFormData();
            });

            // دالة لتحديث سعر المرتجع
            function updateReturnPrice(tr) {
                let selectedOption = tr.find('.returnProductSelect option:selected');
                if (!selectedOption.length) return;

                let type = $('#invoiceType').val();
                let price = type == 1 ? selectedOption.data('price') || 0 : selectedOption.data('price2') || 0;

                tr.find('.returnPrice').val(price);

                let qtyInput = tr.find('.returnQty');
                if (!qtyInput.val() || parseFloat(qtyInput.val()) <= 0) {
                    qtyInput.val(1);
                }

                calcReturnRowTotal(tr);
                saveFormData();
            }

            // دالة لتحديث السعر والكمية
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
                saveFormData();
            }

            // عند اختيار منتج من Select2 في جدول الفاتورة
            $('#itemsTable').on('select2:select', '.productSelect', function(e) {

                let tr = $(this).closest('tr');
                let data = e.params.data;
                let stock = parseFloat(data.stock) || 0;
                tr.find('.qty1').val(stock);
                if (stock <= 0) {
                    alert('⚠ المنتج غير متوفر حالياً');
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
                            tr.find('.qty1').val(0);
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
                saveFormData();
            });

            // عند اختيار منتج من Select2 في جدول المرتجعات
            $('#returnsTable').on('select2:select', '.returnProductSelect', function(e) {
                let tr = $(this).closest('tr');
                let data = e.params.data;

                let found = false;
                $('#returnsTable tbody tr').each(function() {
                    let currentTr = $(this);
                    let selectedVal = currentTr.find('.returnProductSelect').val();
                    if (selectedVal == data.id && currentTr[0] !== tr[0]) {
                        let qtyInput = currentTr.find('.returnQty');
                        qtyInput.val(parseFloat(qtyInput.val() || 0) + 1);
                        calcReturnRowTotal(currentTr);
                        found = true;
                        return false;
                    }
                });

                if (found) {
                    tr.find('.returnProductSelect').val(null).trigger('change');
                    tr.find('.returnBarcode').val('');
                    return;
                }

                let newOption = new Option(data.text, data.id, true, true);
                $(newOption).attr('data-price', data.price)
                    .attr('data-price2', data.price2)
                    .attr('data-barcode', data.barcode);

                tr.find('.returnProductSelect').append(newOption).trigger('change');
                tr.find('.returnBarcode').val(data.barcode);
                tr.find('.returnQty').val(1);
                updateReturnPrice(tr);

                let lastReturnRow = $('#returnsTable tbody tr:last');
                if (lastReturnRow.find('.returnProductSelect').val()) {
                    addReturnRow();
                    $('#returnsTable tbody tr:last .returnBarcode').focus();
                }
                saveFormData();
            });


            // --- قراءة الباركود الموحدة (للفاتورة والمرتجع) ---
            $('#itemsTable, #returnsTable').on('input', '.barcode', function() {
                let val = $(this).val().trim();
                if (val.length !== 6) return;

                let tr = $(this).closest('tr');
                let isReturnTable = tr.closest('#returnsTable').length > 0;
                let selectClass = isReturnTable ? '.returnProductSelect' : '.productSelect';
                let qtyClass = isReturnTable ? '.returnQty' : '.qty';
                let calcFn = isReturnTable ? calcReturnRowTotal : calcRowTotal;
                let updateFn = isReturnTable ? updateReturnPrice : updatePriceAndQty;
                let addFn = isReturnTable ? addReturnRow : addRow;
                let targetTable = isReturnTable ? '#returnsTable' : '#itemsTable';

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
                            alert('المنتج غير موجود');
                            tr.find('.barcode').val('');
                            tr.find(selectClass).val(null).trigger('change');
                            return;
                        }

                        let item = data.results[0];
                        let stock = parseFloat(item.stock) || 0;

                        if (!isReturnTable && stock <= 0) {
                            alert('⚠ المنتج غير متوفر حالياً');
                            tr.find('.barcode').val('');
                            return;
                        }
                        tr.find('.qty1').val(stock);
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
                                tr.find('.qty1').val(0);
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
                        saveFormData();
                    }
                });
            });


            // التحقق من الكمية يدوياً في جدول الفاتورة
            $('#itemsTable').on('input', '.qty', function() {
                let tr = $(this).closest('tr');
                let stock = parseFloat(tr.find('.productSelect option:selected').data('stock')) || 0;
                let qty = parseFloat($(this).val()) || 0;
                if (qty > stock) {
                    $(this).val(stock);
                    alert('⚠ الكمية المطلوبة أكبر من المتاحة: ' + stock);
                }
                if (qty < 1) $(this).val(1);
                calcRowTotal(tr);
                saveFormData();
            });

            // عند تغيير نوع الفاتورة
            $('#invoiceType').on('change', function() {
                $('#itemsTable tbody tr').each(function() {
                    updatePriceAndQty($(this));
                });

                $('#returnsTable tbody tr').each(function() {
                    updateReturnPrice($(this));
                });
                saveFormData();
            });


            // ==================== دوال الكاميرا (تم دمجها) ====================

            let html5QrInvoice = null;
            let isRunningInvoice = false;
            let pauseScanInvoice = false;

            function onScanSuccessInvoice(decodedText) {
                if (pauseScanInvoice) return;
                pauseScanInvoice = true;

                // alert("تم مسح الباركود: " + decodedText); // تعطيل الـ Alert لتسريع العملية
                alert("تم مسح الباركود: " + decodedText);
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

                    html5QrInvoice.start({
                                facingMode: "environment"
                            }, {
                                fps: 10,
                                qrbox: 250
                            },
                            onScanSuccessInvoice
                        )
                        .then(() => {
                            isRunningInvoice = true;
                            btn.html('<i class="bx bx-stop-circle"></i>');
                        })
                        .catch(err => {
                            console.error(err);
                            alert("{{ __('admin.Camera startup failed') }}: " + err);
                        });

                } else {
                    html5QrInvoice.stop().then(() => {
                        isRunningInvoice = false;
                        btn.html('<i class="bx bx-camera"></i>');
                    }).catch(err => {
                        console.error(err);
                        alert("{{ __('admin.Camera stop failed') }}: " + err);
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

                // alert("تم مسح الباركود: " + decodedText); // تعطيل الـ Alert لتسريع العملية
                alert("تم مسح الباركود: " + decodedText);
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

                    html5QrReturns.start({
                                facingMode: "environment"
                            }, {
                                fps: 10,
                                qrbox: 250
                            },
                            onScanSuccessReturns
                        )
                        .then(() => {
                            isRunningReturns = true;
                            btn.html('<i class="bx bx-stop-circle"></i>');
                        })
                        .catch(err => {
                            console.error(err);
                            alert("{{ __('admin.Camera startup failed') }}: " + err);
                        });

                } else {
                    html5QrReturns.stop().then(() => {
                        isRunningReturns = false;
                        btn.html('<i class="bx bx-camera"></i>');
                    }).catch(err => {
                        console.error(err);
                        alert("{{ __('admin.Camera stop failed') }}: " + err);
                    });
                }
            });

        });
    </script>
@endsection
