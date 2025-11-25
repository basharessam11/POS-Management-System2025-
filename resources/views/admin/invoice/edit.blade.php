@extends('admin.layout.app')

@section('page', __('admin.Invoice_Management'))

@section('contant')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">{!! __('admin.Edit') !!} {{ __('admin.Invoice') }}</h5>
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

                            <form action="{{ route('invoice.update', $invoice->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3 g-3">

                                    {{-- Branch --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{{ __('admin.Branch') }}</label>
                                        <select name="branch_id" class="form-control select2" required>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ $invoice->branch_id == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Customer --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{{ __('admin.Customer') }}</label>
                                        <select name="customer_id" class="form-control select2" disabled id="customerSelect"
                                            required>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}" data-balance="{{ $customer->price }}"
                                                    {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Invoice Type --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{{ __('admin.Invoice Type') }}</label>
                                        <select name="type" disabled class="form-control select2" id="invoiceType"
                                            required>
                                            <option value="1" {{ $invoice->type == 1 ? 'selected' : '' }}>
                                                {{ __('admin.Retail') }}</option>
                                            <option value="2" {{ $invoice->type == 2 ? 'selected' : '' }}>
                                                {{ __('admin.Wholesale') }}
                                            </option>
                                        </select>
                                    </div>

                                    {{-- Seller --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{{ __('admin.Seller') }}</label>
                                        <select name="user_id" class="form-control select2" required>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ $invoice->user_id == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Items Table --}}
                                    <div class="col-12">
                                        <h6 class="mt-4">{{ __('admin.Products') }}</h6>

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
                                                    @foreach ($invoice->items as $item)
                                                        <tr>
                                                            <td>
                                                                <input type="text" class="form-control barcode"
                                                                    placeholder="{{ __('admin.Scan Barcode') }}"
                                                                    value="{{ $item->productItem->barcode }}">
                                                            </td>

                                                            <td>
                                                                <select name="product_item_id[]"
                                                                    class="form-control select2 productSelect" required>
                                                                    <option value="{{ $item->productItem->id }}"
                                                                        data-price="{{ $item->productItem->sell_price }}"
                                                                        data-price2="{{ $item->productItem->sell_price2 }}"
                                                                        data-stock="{{ $item->productItem->qty ?? 0 }}"
                                                                        data-barcode="{{ $item->productItem->barcode }}"
                                                                        selected>
                                                                        {{ $item->productItem->product->name }} —
                                                                        {{ $item->productItem->size }} —
                                                                        {{ optional(optional($item->productItem->product)->brand)->name }}
                                                                    </option>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input type="number" name="qty[]"
                                                                    class="form-control qty" min="1"
                                                                    data-qty="{{ $item->qty + $item->productItem->qty ?? 0 }}"
                                                                    value="{{ $item->qty }}">
                                                            </td>

                                                            <td>
                                                                <input type="number" name="price[]"
                                                                    class="form-control price" min="0"
                                                                    value="{{ $item->price }}" readonly>
                                                            </td>

                                                            <td>
                                                                <input type="number" class="form-control rowTotal" readonly
                                                                    value="{{ $item->total }}">
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

                                        <button type="button" class="btn btn-primary mt-4"
                                            id="addRow">{{ __('admin.Add Product') }}</button>
                                    </div>


                                    {{-- TOTAL --}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Total') }}</label>
                                        <input type="number" class="form-control" id="total" name="total" readonly
                                            value="{{ $invoice->total }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Discount') }}</label>
                                        <input type="number" class="form-control" id="discount" name="discount"
                                            value="{{ $invoice->discount }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Net') }}</label>
                                        <input type="number" class="form-control net" id="net" name="net"
                                            readonly value="{{ $invoice->total - $invoice->discount }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Paid') }}</label>
                                        <input type="number" class="form-control" id="paid" name="paid"
                                            value="{{ $invoice->paid }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Remaining') }}</label>
                                        <input type="number" class="form-control" id="remaining" name="remaining"
                                            readonly value="{{ $invoice->remaining }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Previous Balance') }}</label>
                                        <input type="number" class="form-control" id="price" readonly
                                            value="{{ $invoice->customer->price }}">
                                    </div>

                                    {{-- Note --}}
                                    <div class="col-12 col-md-12">
                                        <label class="form-label">{{ __('admin.Notes') }}</label>
                                        <textarea name="note" class="form-control" rows="2">{{ $invoice->note }}</textarea>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="d-flex justify-content-end gap-3 mt-4">
                                        <button type="submit" name="save" value="save"
                                            class="btn btn-success">{{ __('admin.Save Invoice') }}</button>
                                        <button type="submit" name="save" value="print"
                                            class="btn btn-primary">{{ __('admin.Save and Print') }}</button>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('footer')
    <script>
        $(document).ready(function() {
            $('form').on('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // منع الإرسال
                    return false;
                }
            });
            // حساب الصف والمجموع
            function calcRowTotal(tr) {
                let qty = parseFloat(tr.find('.qty').val()) || 0;
                let price = parseFloat(tr.find('.price').val()) || 0;
                tr.find('.rowTotal').val(qty * price);
                calcTotal();
            }

            function calcTotal() {
                let total = 0;
                $('.rowTotal').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                let discount = parseFloat($('#discount').val()) || 0;
                let net = total - discount;
                $('#total').val(total);
                $('#net').val(net);

                let paid = parseFloat($('#paid').val()) || 0;
                if (paid === 0 || paid === $('#net').data('prevNet')) {
                    paid = net;
                    $('#paid').val(paid);
                }

                $('#remaining').val(net - paid);
                $('#net').data('prevNet', net);
            }

            $('#discount,#paid').on('input', calcTotal);

            // اضافة صف جديد
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

            // تهيئة Select2
            function initSelect2(select) {
                select.select2({
                    placeholder: '{{ __('admin.Search...') }}',
                    ajax: {
                        url: '{{ route('invoice.search') }}',
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

            $('.productSelect').each(function() {
                initSelect2($(this));
            });
            $('#itemsTable').on('focus', '.productSelect', function() {
                initSelect2($(this));
            });

            // دالة لتحديث السعر والكمية للصف
            function updatePriceAndQty(tr) {
                let selectedOption = tr.find('.productSelect option:selected');
                if (!selectedOption.length) return;

                let type = $('#invoiceType').val();
                let price = type == 1 ? selectedOption.data('price') || 0 : selectedOption.data('price2') || 0;
                tr.find('.price').val(price);

                // ضبط الكمية الافتراضية إذا كانت صفر
                let qtyInput = tr.find('.qty');
                if (!qtyInput.val() || parseFloat(qtyInput.val()) <= 0) {
                    qtyInput.val(1);
                }

                calcRowTotal(tr);
            }


            // عند تغيير نوع الفاتورة
            $('#invoiceType').on('change', function() {
                $('#itemsTable tbody tr').each(function() {
                    let tr = $(this);
                    let selectedOption = tr.find('.productSelect option:selected');
                    if (selectedOption.length) {
                        let data = {
                            price: selectedOption.data('price'),
                            price2: selectedOption.data('price2'),
                            stock: selectedOption.data('stock')
                        };
                        updatePriceAndQty(tr, data);
                    }
                });
            });

            // عند اختيار منتج من القايمة
            $('#itemsTable').on('select2:select', '.productSelect', function(e) {
                let tr = $(this).closest('tr');
                let data = e.params.data;
                let stock = parseFloat(data.stock) || 0;

                // المنتج غير متوفر
                if (stock <= 0) {
                    alert('⚠ المنتج غير متوفر حالياً');
                    $(this).val(null).trigger('change');
                    return;
                }


                let addedQty = 1;
                let found = false;

                // هل المنتج موجود مسبقاً في صف آخر؟ → زوّد الكمية فقط
                $('#itemsTable tbody tr').each(function() {
                    if ($(this)[0] !== tr[0]) {
                        let existing = $(this).find('.productSelect').val();
                        if (existing == data.id) {

                            let qtyInput = $(this).find('.qty');
                            let currentQty = parseFloat(qtyInput.val()) || 0;
                            let maxQty = parseFloat(qtyInput.attr('data-qty')) || stock;
                            let newQty = currentQty + addedQty;


                            if (newQty > maxQty) {
                                newQty = maxQty;
                                alert('⚠ الكمية المطلوبة أكبر من المتاحة: ' + maxQty);
                            }

                            qtyInput.val(newQty);
                            calcRowTotal($(this));

                            if ($(this)[0] !== tr[0]) {
                                tr.find('.productSelect').val(null).trigger('change');
                                tr.find('.barcode').val('');
                            }

                            found = true;
                            return false;
                        }
                    }
                });

                // لو كان موجود قبل كده خلاص وقف
                if (found) return;

                // لو أول مرة يختار المنتج → لازم نضيف الـ option الأول
                let newOption = new Option(data.text, data.id, true, true);
                $(newOption)
                    .attr('data-price', data.price)
                    .attr('data-price2', data.price2)
                    .attr('data-stock', data.stock)
                    .attr('data-barcode', data.barcode || '');

                tr.find('.productSelect').append(newOption).trigger('change');

                // املأ الباركود
                tr.find('.barcode').val(data.barcode || '');

                // لازم الكمية ترجع دايمًا 1 عند أول اختيار
                tr.find('.qty').val(1);

                // حدّث السعر والإجمالي
                updatePriceAndQty(tr);

                // لو آخر صف فيه منتج → افتح صف جديد
                let lastRow = $('#itemsTable tbody tr:last');

                if (lastRow.find('.productSelect').val()) {
                    addRow();
                    $('#itemsTable tbody tr:last .barcode').focus();
                }
            });


            // قراءة الباركود
            // قراءة الباركود فقط عند 6 أرقام
            $('#itemsTable').on('input', '.barcode', function() {
                let val = $(this).val().trim();
                if (val.length !== 6) return; // أول ما يكون 6 أرقام فقط

                let tr = $(this).closest('tr');

                $.ajax({
                    url: '{{ route('invoice.search') }}',
                    data: {
                        q: val
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.results.length == 0) {
                            alert('المنتج غير موجود');
                            tr.find('.barcode').val('');
                            tr.find('.productSelect').val(null).trigger('change');
                            return;
                        }

                        let item = data.results[0];
                        let stock = parseFloat(item.stock) || 0;
                        if (stock <= 0) {
                            alert('⚠ المنتج غير متوفر حالياً');
                            tr.find('.barcode').val('');
                            return;
                        }

                        let found = false;

                        // التحقق إذا المنتج موجود مسبقاً
                        $('#itemsTable tbody tr').each(function() {
                            let existing = $(this).find(
                                '.productSelect option:selected').val();
                            if (existing == item.id) {
                                let qtyInput = $(this).find('.qty');
                                let newQty = parseFloat(qtyInput.val()) + 1;
                                if (newQty > qtyInput.data('qty')) newQty = qtyInput
                                    .data('qty');
                                qtyInput.val(newQty);
                                calcRowTotal($(this));

                                tr.find('.barcode').val('');
                                tr.find('.productSelect').val(null).trigger('change');
                                found = true;
                                return false;
                            }
                        });

                        if (!found) {
                            let newOption = new Option(item.text, item.id, true, true);
                            $(newOption).attr('data-price', item.price)
                                .attr('data-price2', item.price2)
                                .attr('data-stock', item.stock)
                                .attr('data-barcode', item.barcode);

                            tr.find('.productSelect').append(newOption).trigger('change');
                            tr.find('.barcode').val(item.barcode);
                            tr.find('.qty').val(1);
                            updatePriceAndQty(tr);

                            // إضافة صف جديد إذا آخر صف فيه منتج
                            let lastRow = $('#itemsTable tbody tr:last');
                            if (lastRow.find('.productSelect').val()) {
                                addRow();
                                $('#itemsTable tbody tr:last .barcode').focus();
                            }
                        }
                    }
                });
            });

            // التحقق من الكمية يدوياً
            $('#itemsTable').on('input', '.qty', function() {
                let tr = $(this).closest('tr');
                let stock = parseFloat(tr.find('.qty').data('qty')) || parseFloat(tr.find(
                    '.productSelect option:selected').data('stock'));
                let qty = parseFloat($(this).val()) || 0;
                if (qty > stock) {
                    $(this).val(stock);
                    alert('⚠ الكمية المطلوبة أكبر من المتاحة: ' + stock);
                }
                if (qty < 1) $(this).val(1);
                calcRowTotal(tr);
            });

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

                alert("تم مسح الباركود: " + decodedText);

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
                        alert("لم يتم العثور على كاميرا");
                        return;
                    }

                    let backCam = devices.find(cam =>
                        cam.label.toLowerCase().includes("back") ||
                        cam.label.toLowerCase().includes("environment")
                    );
                    let camId = backCam ? backCam.id : devices[0].id;

                    html5QrInvoice.start({ facingMode: "environment" },  {
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
        });
    </script>
@endsection
@endsection
