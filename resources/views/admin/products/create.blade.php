@extends('admin.layout.app')

@section('page', __('admin.Products_Management'))

@section('contant')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">{!! __('admin.Add') !!} {!! __('admin.Products') !!}</h5>
                            <button type="button" class="btn btn-label-secondary" id="clearDraftButton">
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

                            {{-- Form --}}
                            {{-- IMPORTANT: Added id="addProductForm" for JS targeting --}}
                            <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data"
                                id="addProductForm">
                                @csrf

                                <div class="row mb-3 g-3">

                                    {{-- Name --}}
                                    <div class="col-12 col-md-12">
                                        <label class="form-label">{!! __('admin.Name') !!}</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name') }}" required>
                                    </div>

                                    {{-- Category --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Category') !!}</label>
                                        <select class="form-control select2" name="category_id" required>
                                            <option value="">{!! __('admin.select') !!}</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Brand --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Brand') !!}</label>
                                        <select class="form-control select2" name="brand_id" required>
                                            <option value="">{!! __('admin.select') !!}</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Warehouse') !!}</label>
                                        <select name="warehouse_id" class="form-control select2 " required>
                                            @foreach ($warehouses as $index => $warehouse)
                                                <option value="{{ $warehouse->id }}" {{ $index == 0 ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Branch') !!}</label>
                                        <select name="branch_id" class="form-control select2" required>
                                            @foreach ($branches as $index => $branch)
                                                <option value="{{ $branch->id }}" {{ $index == 0 ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Product Items Table --}}
                                    <div class="col-12">
                                        <h6 class="mt-4">{!! __('admin.Item') !!}</h6>

                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="productItemsTable">
                                                <thead>
                                                    <tr>
                                                        <th style="min-width: 140px;">{!! __('admin.Size') !!}</th>
                                                        <th style="min-width: 100px;">{!! __('admin.Color') !!}</th>
                                                        <th style="min-width: 140px;">{!! __('admin.Price') !!}</th>
                                                        <th style="min-width: 140px;">{!! __('admin.Sell_Price') !!}</th>
                                                        <th style="min-width: 140px;">{!! __('admin.Wholesale_Price') !!}</th>
                                                        <th style="min-width: 100px;">{!! __('admin.Quantity') !!}</th>
                                                        <th style="min-width: 100px;">{!! __('admin.Min Quantity') !!}</th>
                                                        <th style="min-width: 100px;">{!! __('admin.Photo') !!}</th>
                                                        <th style="min-width: 90px;">{!! __('admin.Delete') !!}</th>
                                                    </tr>
                                                </thead>

                                                {{-- The JS will manage the rows here, but we keep the initial loop for any initial data --}}
                                                <tbody>
                                                    {{-- The loop below is for *initial* data, but it will be overridden by the draft loading if a draft exists --}}
                                                    {{-- If you only want the table to be empty by default (when no draft), you can remove this loop and just have an empty <tbody> --}}
                                                    @foreach ($sizes as $size)
                                                        <tr>
                                                            <td>
                                                                <input type="text" name="sizes[]"
                                                                    class="form-control item-input"
                                                                    value="{{ $size }}" readonly>
                                                            </td>

                                                            <td>
                                                                <input type="text" name="colors[]" value="ابيض"
                                                                    class="form-control item-input" required>
                                                            </td>

                                                            <td>
                                                                <input type="number" min="0" value="0"
                                                                    name="prices[]" class="form-control item-input"
                                                                    required>
                                                            </td>

                                                            <td>
                                                                <input type="number" min="0" value="0"
                                                                    name="sell_prices[]" class="form-control item-input"
                                                                    required>
                                                            </td>

                                                            <td>
                                                                <input type="number" min="0" value="0"
                                                                    name="sell_prices2[]" class="form-control item-input"
                                                                    required>
                                                            </td>

                                                            <td>
                                                                <input type="number" min="1" name="qtys[]"
                                                                    value="1" class="form-control item-input" required>
                                                            </td>

                                                            <td>
                                                                <input type="number" min="1" name="min_qtys[]"
                                                                    value="1" class="form-control item-input" required>
                                                            </td>

                                                            <td>
                                                                {{-- NOTE: We cannot save file input values, so this will be empty upon reload --}}
                                                                <input type="file" name="photo[]" class="form-control"
                                                                    accept="image/*">
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

                                        <button type="button" class="btn btn-primary mt-4" id="addRow">
                                            {!! __('admin.Add Product') !!}
                                        </button>
                                    </div>


                                    {{-- Submit and Clear Draft Buttons --}}
                                    <div class="d-flex justify-content-end gap-3 mt-4">

                                        <button type="submit" class="btn btn-success">{!! __('admin.Save') !!}</button>
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
            // Key used to store the draft data in localStorage
            const DRAFT_KEY = 'addProductFormDraft';
            const $form = $('#addProductForm');
            const $tableBody = $('#productItemsTable tbody');

            // --- Row Management Functions (Existing) ---

            function createProductItemRowHtml(data = {}) {
                // Ensure default values are set for new rows
                const defaultData = {
                    size: '',
                    color: 'ابيض',
                    price: 0,
                    sell_price: 0,
                    sell_price2: 0,
                    qty: 1,
                    min_qty: 1
                };

                const item = {
                    ...defaultData,
                    ...data
                };

                // The 'readonly' attribute for 'sizes[]' is only applied if a value is provided (e.g., loaded from draft or initial loop)
                // For a newly added row, we want it editable.
                const sizeReadonly = item.size ? 'readonly' : '';

                return `<tr>
                    <td><input type="text" name="sizes[]" class="form-control item-input" value="${item.size}" ${sizeReadonly}></td>
                    <td><input type="text" name="colors[]" class="form-control item-input" value="${item.color}" required></td>
                    <td><input type="number" min="0" name="prices[]" class="form-control item-input" value="${item.price}" required></td>
                    <td><input type="number" min="0" name="sell_prices[]" class="form-control item-input" value="${item.sell_price}" required></td>
                    <td><input type="number" min="0" name="sell_prices2[]" class="form-control item-input" value="${item.sell_price2}" required></td>
                    <td><input type="number" min="1" name="qtys[]" class="form-control item-input" value="${item.qty}" required></td>
                    <td><input type="number" min="1" name="min_qtys[]" class="form-control item-input" value="${item.min_qty}" required></td>
                    <td><input type="file" name="photo[]" class="form-control"></td>
                    <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="bx bxs-trash"></i></button></td>
                </tr>`;
            }

            $('#addRow').click(function() {
                $tableBody.append(createProductItemRowHtml());
            });

            // حذف صف
            $('#productItemsTable').on('click', '.removeRow', function() {
                if ($tableBody.find('tr').length > 1) {
                    $(this).closest('tr').remove();
                    saveDraft(); // Save draft immediately after removing a row
                } else {
                    alert('لا يمكن حذف الصف الأخير');
                }
            });

            // --- Draft/Caching Functions (New) ---

            // 1. Function to SAVE the form data
            function saveDraft() {
                const draft = {};

                // Save simple form fields (name, category_id, brand_id, etc.)
                $form.find(':input:not([type="submit"], [type="button"], [type="file"])').each(function() {
                    const $input = $(this);
                    const name = $input.attr('name');
                    const value = $input.val();
                    const type = $input.attr('type');

                    if (name) {
                        if ($input.is('select')) {
                            draft[name] = value;
                        } else if (type === 'checkbox' || type === 'radio') {
                            if ($input.is(':checked')) {
                                draft[name] = value;
                            }
                        } else if (name.indexOf('[]') === -1) { // Skip product item array inputs for now
                            draft[name] = value;
                        }
                    }
                });

                // Save product item rows separately
                const items = [];
                $tableBody.find('tr').each(function() {
                    const $row = $(this);
                    const item = {
                        size: $row.find('input[name="sizes[]"]').val(),
                        color: $row.find('input[name="colors[]"]').val(),
                        price: $row.find('input[name="prices[]"]').val(),
                        sell_price: $row.find('input[name="sell_prices[]"]').val(),
                        sell_price2: $row.find('input[name="sell_prices2[]"]').val(),
                        qty: $row.find('input[name="qtys[]"]').val(),
                        min_qty: $row.find('input[name="min_qtys[]"]').val()
                    };
                    items.push(item);
                });
                draft.product_items = items;

                localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
            }

            // 2. Function to LOAD the form data
            function loadDraft() {
                const draftData = localStorage.getItem(DRAFT_KEY);
                if (draftData) {
                    const draft = JSON.parse(draftData);

                    // Restore simple form fields
                    $.each(draft, function(name, value) {
                        if (name !== 'product_items') {
                            const $input = $form.find(`[name="${name}"]`);
                            if ($input.is('select')) {
                                $input.val(value).trigger(
                                    'change'); // .trigger('change') helps with select2
                            } else if ($input.attr('type') === 'checkbox' || $input.attr('type') ===
                                'radio') {
                                // Logic for restoring checkboxes/radios if you had them
                            } else {
                                $input.val(value);
                            }
                        }
                    });

                    // Restore product item rows
                    if (draft.product_items && draft.product_items.length > 0) {
                        $tableBody.empty(); // Clear existing rows (including the initial ones from the Blade loop)
                        draft.product_items.forEach(function(item) {
                            $tableBody.append(createProductItemRowHtml(item));
                        });
                    }

                    // Show a notification that a draft was loaded
                    // console.log('Draft data loaded successfully.');
                    // You might want to display a visual alert here for the user
                }
            }

            // 3. Function to CLEAR the form data and draft
            function clearDraft() {
                if (confirm(
                        '{{ __('admin.Are you sure you want to delete all saved data and start from scratch?') }}'
                    )) {

                    // Clear simple form fields
                    $form.find(':input:not([type="submit"], [type="button"])').each(function() {
                        const $input = $(this);
                        const name = $input.attr('name');
                        if (name && name.indexOf('[]') === -1) {
                            // Reset value, but be careful with select defaults
                            if ($input.is('select')) {
                                // This resets all selects to their first option or a designated default
                                $input.find('option:first').prop('selected', true).trigger('change');
                            } else {
                                $input.val('');
                            }
                        }
                    });

                    // Reset product items table to its initial state (or empty)
                    $tableBody.empty();
                    // Optional: Re-add a single blank row
                    $tableBody.append(createProductItemRowHtml());


                    // Clear localStorage draft
                    localStorage.removeItem(DRAFT_KEY);
                    // console.log('Draft cleared.');
                    window.location.reload();
                }
            }

            // --- Event Listeners ---

            // 1. Load draft on page load
            loadDraft();

            // 2. Save draft on input change or keyup in the form
            // Use 'change' for selects/checkboxes/radios and 'keyup' for text/number inputs
            $form.on('change keyup', ':input:not([type="submit"], [type="button"], [type="file"])', function() {
                saveDraft();
            });

            // 3. Clear draft button click
            $('#clearDraftButton').click(function() {
                clearDraft();
            });

            // 4. Clear draft when form is successfully submitted (optional, but good practice)
            $form.submit(function() {
                // You can clear the draft here, but it's often safer to clear it in the Laravel controller
                // *after* the successful database save, to ensure the data is truly persisted.
                // For a client-side solution, you can clear it right before submission.
                localStorage.removeItem(DRAFT_KEY);
            });
        });
    </script>
@endsection

@endsection
