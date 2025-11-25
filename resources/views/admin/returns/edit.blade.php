@extends('admin.layout.app')

@section('page', __('admin.Invoice_Management'))

@section('contant')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">

                        <div class="card-header">
                            <h5 class="card-title m-0">{!! __('admin.Edit') !!} {!! __('admin.Returns') !!}</h5>
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

                            <form action="{{ route('returns.update', $invoice->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3 g-3">

                                    {{-- Branch --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{{ __('admin.Branch') }}</label>
                                        <select name="branch_id" class="form-control select2" required disabled>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Customer --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{{ __('admin.Customer') }}</label>
                                        <select name="customer_id" class="form-control select2" id="customerSelect" required
                                            disabled>

                                            <option value="{{ $customer->id }}" data-balance="{{ $customer->price }}">
                                                {{ $customer->name }}
                                            </option>

                                        </select>
                                    </div>

                                    {{-- Invoice Type --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{{ __('admin.Invoice Type') }}</label>
                                        <select name="type" class="form-control select2" id="invoiceType" required
                                            disabled>
                                            <option value="1" {{ $invoice->type == 1 ? 'selected' : '' }}>
                                                {{ __('admin.Retail') }}</option>
                                            <option value="2" {{ $invoice->type == 2 ? 'selected' : '' }}>
                                                {{ __('admin.Wholesale') }}</option>
                                        </select>
                                    </div>

                                    {{-- Seller --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{{ __('admin.Seller') }}</label>
                                        <select name="user_id" class="form-control select2" required disabled>
                                            <option value="{{ auth()->id() }}">{{ auth()->user()->name }}</option>
                                        </select>
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
                                                    @foreach ($invoice->returnItems as $item)
                                                        <tr>
                                                            <td>
                                                                <div style="display: flex; gap: 4px; align-items: center;">
                                                                    <input type="text"
                                                                        class="form-control barcode returnBarcode"
                                                                        placeholder="{{ __('admin.Scan Barcode') }}"
                                                                        style="flex: 1;"
                                                                        value="{{ $item->productItem->barcode }}">

                                                                </div>
                                                            </td>
                                                            <td>
                                                                <select name="return_product_item_id[]"
                                                                    class="form-control select2 returnProductSelect">
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
                                                            <td><input type="number" name="return_qty[]"
                                                                    class="form-control returnQty" min="1"
                                                                    data-qty="{{ $item->qty + $item->productItem->qty ?? 0 }}"
                                                                    value="{{ $item->qty }}"></td>
                                                            <td><input type="number" name="return_price[]"
                                                                    class="form-control returnPrice" min="0"
                                                                    value="{{ $item->price }}"></td>
                                                            <td><input type="number" class="form-control returnTotal"
                                                                    readonly value="{{ $item->total }}"></td>
                                                            <td><button type="button"
                                                                    class="btn btn-danger btn-sm removeReturnRow"><i
                                                                        class="bx bxs-trash"></i></button></td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td>
                                                            <div style="display: flex; gap: 4px; align-items: center;">
                                                                <input type="text"
                                                                    class="form-control barcode returnBarcode"
                                                                    placeholder="{{ __('admin.Scan Barcode') }}"
                                                                    style="flex: 1;">

                                                            </div>
                                                        </td>
                                                        <td>
                                                            <select name="return_product_item_id[]"
                                                                class="form-control select2 returnProductSelect">
                                                                <option value="">{{ __('admin.Select Product') }}
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td><input type="number" name="return_qty[]"
                                                                class="form-control returnQty" min="1"
                                                                value="1"></td>
                                                        <td><input type="number" name="return_price[]"
                                                                class="form-control returnPrice" min="0"
                                                                value="0"></td>
                                                        <td><input type="number" class="form-control returnTotal"
                                                                readonly value="0"></td>
                                                        <td><button type="button"
                                                                class="btn btn-danger btn-sm removeReturnRow"><i
                                                                    class="bx bxs-trash"></i></button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <button type="button" class="btn btn-warning mt-3"
                                            id="addReturnRow">{{ __('admin.Add Return') }}</button>

                                        <div class="col-12 col-md-12 mt-3">
                                            <label class="form-label">{{ __('admin.Total Returns') }}</label>
                                            <input type="number" class="form-control" id="returnsTotal"
                                                name="returns_total" readonly value="0">
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-4 mt-3" style="display: none">
                                        <label class="form-label">{{ __('admin.Net') }}</label>
                                        <input type="number" class="form-control net" id="net" name="net"
                                            readonly value="{{ $invoice->total }}">
                                    </div>
                                    <div class="col-12 col-md-4 mt-3">
                                        <label class="form-label">{{ __('admin.Paid') }}</label>
                                        <input type="number" class="form-control" id="paid" name="paid"
                                            value="{{ $invoice->paid }}">
                                    </div>

                                    <div class="col-12 col-md-4 mt-3" style="display: none">
                                        <label class="form-label">{{ __('admin.Previous Balance') }}</label>
                                        <input type="number" class="form-control" id="price" readonly
                                            value="{{ $invoice->customer->price }}">
                                    </div>

                                    <style>
                                        /* Responsive table for mobile */
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
                                        <textarea name="note" class="form-control" rows="2">{{ $invoice->note }}</textarea>
                                    </div>

                                    <div class="d-flex justify-content-end gap-3 mt-4">
                                        <button type="submit" name="save" value="save"
                                            class="btn btn-success">{{ __('admin.Save') }}</button>

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
            $('form').on('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Prevent submission
                    return false;
                }
            });

            // --- General Calculation Functions ---

            // Calculate row total and grand total for the invoice
            function calcRowTotal(tr) {
                let qty = parseFloat(tr.find('.qty').val()) || 0;
                let price = parseFloat(tr.find('.price').val()) || 0;
                tr.find('.rowTotal').val(qty * price);
                calcTotal(); // Call grand total function after updating any row
            }

            // Calculate total return for a single row
            function calcReturnRowTotal(tr) {
                let qty = parseFloat(tr.find('.returnQty').val()) || 0;
                let price = parseFloat(tr.find('.returnPrice').val()) || 0;
                tr.find('.returnTotal').val(qty * price);
                calcReturnsTotal(); // Call total returns function
            }

            // Calculate total for all returns
            function calcReturnsTotal() {
                let totalReturns = 0;
                $('#returnsTable .returnTotal').each(function() { // Set search scope to #returnsTable
                    totalReturns += parseFloat($(this).val()) || 0;
                });
                $('#returnsTotal').val(totalReturns);
                calcTotal(); // Update grand total of the invoice after calculating returns
            }

            function calcTotal() {
                let total = 0;
                $('#itemsTable .rowTotal').each(function() { // Set search scope to #itemsTable
                    total += parseFloat($(this).val()) || 0;
                });
                let discount = parseFloat($('#discount').val()) || 0;

                // Calculate total returns from returnsTotal field
                let returnsTotal = parseFloat($('#returnsTotal').val()) || 0;

                // Net = Total - Discount - Total Returns
                let net = total - discount - returnsTotal;

                $('#total').val(total);
                $('#net').val(net);

                let paid = parseFloat($('#paid').val()) || 0;
                if (paid === 0 || paid === $('#net').data('prevNet')) {
                    paid = net;
                    $('#paid').val(paid);
                }

                $('#paid').val(net);
                $('#net').data('prevNet', net);
            }

            // Link financial fields to the grand total
            $('#discount, #paid').on('input', calcTotal);
            // calcReturnsTotal is called by calcReturnRowTotal

            // --- Manage Invoice Rows (ItemsTable) ---

            // Add new row (product)
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

            // Link quantity and price fields in the invoice table (ItemsTable)
            $('#itemsTable').on('input', '.qty, .price', function() {
                calcRowTotal($(this).closest('tr'));
            });


            // --- Manage Return Rows (ReturnsTable) ---

            // Add new row (return)
            function addReturnRow() {
                let tr = `<tr>
            <td><input type="text" class="form-control barcode returnBarcode" placeholder="{{ __('admin.Scan Barcode') }}"></td> <td><select name="return_product_item_id[]" class="form-control select2 returnProductSelect"><option value="">{{ __('admin.Select Product') }}</option></select></td>
            <td><input type="number" name="return_qty[]" class="form-control returnQty" min="1" value="1"></td>
            <td><input type="number" name="return_price[]" class="form-control returnPrice" min="0" value="0"></td>
            <td><input type="number" class="form-control returnTotal" readonly value="0"></td>
            <td><button type="button" class="btn btn-danger btn-sm removeReturnRow"><i class="bx bxs-trash"></i></button></td>
        </tr>`;
                $('#returnsTable tbody').append(tr);
                initSelect2($('#returnsTable tbody tr:last .returnProductSelect')); // Initialize Select2 for return
            }

            $('#addReturnRow').click(addReturnRow);

            $('#returnsTable').on('click', '.removeReturnRow', function() {
                $(this).closest('tr').remove();
                calcReturnsTotal();
            });

            // Link quantity and price fields in the returns table (ReturnsTable)
            $('#returnsTable').on('input', '.returnQty, .returnPrice', function() {
                calcReturnRowTotal($(this).closest('tr'));
            });


            // --- Initialize Select2 and Products ---

            // General Select2 Initialization (used for invoice and return tables)
            function initSelect2(select) {
                select.select2({
                    placeholder: '{{ __('admin.Search for product...') }}',
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

            // Function to update the return price when selecting a product or changing the invoice type
            function updateReturnPrice(tr) {
                let selectedOption = tr.find('.returnProductSelect option:selected');
                if (!selectedOption.length) return;

                let type = $('#invoiceType').val();
                // Use retail price (price) or wholesale price (price2)
                let price = type == 1 ? selectedOption.data('price') || 0 : selectedOption.data('price2') || 0;

                tr.find('.returnPrice').val(price);

                // Set default quantity if it is zero
                let qtyInput = tr.find('.returnQty');
                if (!qtyInput.val() || parseFloat(qtyInput.val()) <= 0) {
                    qtyInput.val(1);
                }

                calcReturnRowTotal(tr);
            }

            // Function to update price and quantity for the row (from original code)
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

            // When selecting a product from Select2 in the invoice table
            $('#itemsTable').on('select2:select', '.productSelect', function(e) {
                let tr = $(this).closest('tr');
                let data = e.params.data;
                let stock = parseFloat(data.stock) || 0;

                if (stock <= 0) {
                    alert('{{ __('admin.Product is currently unavailable') }}');
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

            $('#returnsTable').on('select2:select', '.returnProductSelect', function(e) {
                let tr = $(this).closest('tr');
                let data = e.params.data;
                let barcode = data.barcode || '';
                let price = $('#invoiceType').val() == 1 ? data.price : data.price2;

                // Check if the product already exists in any other row
                let found = false;
                $('#returnsTable tbody tr').each(function() {
                    let currentTr = $(this);
                    let selectedVal = currentTr.find('.returnProductSelect').val();
                    if (selectedVal == data.id && currentTr[0] !== tr[0]) {
                        // Increase quantity instead of adding a new row
                        let qtyInput = currentTr.find('.returnQty');
                        qtyInput.val(parseFloat(qtyInput.val() || 0) + 1);
                        calcReturnRowTotal(currentTr);
                        found = true;
                        return false; // exit each
                    }
                });

                if (found) {
                    // Clear the current row selection as it's no longer needed
                    tr.find('.returnProductSelect').val(null).trigger('change');
                    tr.find('.returnBarcode').val('');
                    return;
                }

                // Add data to the current row if it doesn't exist
                let newOption = new Option(data.text, data.id, true, true);
                $(newOption).attr('data-price', data.price)
                    .attr('data-price2', data.price2)
                    .attr('data-barcode', barcode);

                tr.find('.returnProductSelect').append(newOption).trigger('change');
                tr.find('.returnBarcode').val(barcode);
                tr.find('.returnQty').val(1);
                updateReturnPrice(tr);

                // Add a new row if the last one is filled
                let lastReturnRow = $('#returnsTable tbody tr:last');
                if (lastReturnRow.find('.returnProductSelect').val()) {
                    addReturnRow();
                    $('#returnsTable tbody tr:last .returnBarcode').focus();
                }
            });


            // --- Unified Barcode Reading (for Invoice and Return) ---

            $('#itemsTable, #returnsTable').on('input', '.barcode', function() {
                let val = $(this).val().trim();
                if (val.length !== 6) return; // Proceed only when it is 6 digits

                let tr = $(this).closest('tr');
                let isReturnTable = tr.closest('#returnsTable').length > 0;

                let selectClass = isReturnTable ? '.returnProductSelect' : '.productSelect';
                let qtyClass = isReturnTable ? '.returnQty' : '.qty';
                let calcFn = isReturnTable ? calcReturnRowTotal : calcRowTotal;
                let updateFn = isReturnTable ? updateReturnPrice : updatePriceAndQty;
                let addFn = isReturnTable ? addReturnRow : addRow;
                let targetTable = isReturnTable ? '#returnsTable' : '#itemsTable';

                // Prevent repeated search if the current barcode matches the one in Select2
                let currentBarcode = tr.find(selectClass).find('option:selected').data('barcode');
                if (currentBarcode && currentBarcode == val) return;

                $.ajax({
                    url: '{{ route('invoice.search') }}',
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
                            alert('{{ __('admin.Product is currently unavailable') }}');
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



            // Manual quantity check in the invoice table (original code)
            $('#itemsTable').on('input', '.qty', function() {
                let tr = $(this).closest('tr');
                let stock = parseFloat(tr.find('.productSelect option:selected').data('stock')) || 0;
                let qty = parseFloat($(this).val()) || 0;
                if (qty > stock) {
                    $(this).val(stock);
                    alert('{{ __('admin.The requested quantity is greater than the available quantity:') }} ' +
                        stock);
                }
                if (qty < 1) $(this).val(1);
                calcRowTotal(tr);
            });

            // When changing invoice type (to update prices in both tables)
            $('#invoiceType').on('change', function() {
                // Update invoice product prices
                $('#itemsTable tbody tr').each(function() {
                    updatePriceAndQty($(this));
                });

                // Update return product prices
                $('#returnsTable tbody tr').each(function() {
                    updateReturnPrice($(this));
                });
            });


            // --- Initialization on Load ---

            // Initialize Select2 for existing rows
            $('.productSelect').each(function() {
                initSelect2($(this));
            });
            $('.returnProductSelect').each(function() {
                initSelect2($(this));
            });

            // Add an empty row on load if the returns table is empty
            if ($('#returnsTable tbody tr').length === 0) {
                addReturnRow();
            }

            calcReturnsTotal();


        });
    </script>
    <script>
        $(document).ready(function() {

            // ==================== Invoice Table ====================
            let html5QrInvoice = null;
            let isRunningInvoice = false;
            let pauseScanInvoice = false;

            function onScanSuccessInvoice(decodedText) {
                if (pauseScanInvoice) return;
                pauseScanInvoice = true;

                alert("{{ __('admin.Barcode scanned:') }}" + decodedText);

                // Find the first empty row
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

                    html5QrInvoice.start(camId, {
                            fps: 10,
                            qrbox: 250
                        }, onScanSuccessInvoice)
                        .then(() => {
                            isRunningInvoice = true;
                            btn.html('<i class="bx bx-stop-circle"></i>');
                        })
                        .catch(err => {
                            console.error(err);
                            alert("{{ __('admin.Failed to start camera:') }}" + err);
                        });

                } else {
                    html5QrInvoice.stop().then(() => {
                        isRunningInvoice = false;
                        btn.html('<i class="bx bx-camera"></i>');
                    }).catch(err => {
                        console.error(err);
                        alert("{{ __('admin.Failed to stop camera:') }}" + err);
                    });
                }
            });

            // ==================== Returns Table ====================
            let html5QrReturns = null;
            let isRunningReturns = false;
            let pauseScanReturns = false;

            function onScanSuccessReturns(decodedText) {
                if (pauseScanReturns) return;
                pauseScanReturns = true;

                alert("{{ __('admin.Barcode scanned:') }}" + decodedText);

                // Find first empty row in returns table
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

                    html5QrReturns.start(camId, {
                            fps: 10,
                            qrbox: 250
                        }, onScanSuccessReturns)
                        .then(() => {
                            isRunningReturns = true;
                            btn.html('<i class="bx bx-stop-circle"></i>');
                        })
                        .catch(err => {
                            console.error(err);
                            alert("{{ __('admin.Failed to start camera:') }}" + err);
                        });

                } else {
                    html5QrReturns.stop().then(() => {
                        isRunningReturns = false;
                        btn.html('<i class="bx bx-camera"></i>');
                    }).catch(err => {
                        console.error(err);
                        alert("{{ __('admin.Failed to stop camera:') }}" + err);
                    });
                }
            });

        });
    </script>
@endsection
