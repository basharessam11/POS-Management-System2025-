@extends('admin.layout.app')

@section('page', __('admin.Edit_Product'))

@section('contant')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">{!! __('admin.Edit') !!} {!! __('admin.Products') !!}</h5>
                        </div>
                        <div class="card-body">

                            {{-- Alerts --}}
                            @if (session('success'))
                                <div class="alert alert-success text-center">{{ session('success') }}</div>
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
                            <form action="{{ route('products.update', $product->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3 g-3">

                                    {{-- Name --}}
                                    <div class="col-12 col-md-12">
                                        <label class="form-label">{!! __('admin.Name') !!}</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $product->name) }}" required>
                                    </div>

                                    {{-- Category --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Category') !!}</label>
                                        <select class="form-control select2" name="category_id" required>
                                            <option value="">{!! __('admin.select') !!}</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
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
                                                    {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Warehouse --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Warehouse') !!}</label>
                                        <select name="warehouse_id" class="form-control select2" required>
                                            @foreach ($warehouses as $index => $warehouse)
                                                <option value="{{ $warehouse->id }}"
                                                    {{ old('warehouse_id', $product->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Branch --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Branch') !!}</label>
                                        <select name="branch_id" class="form-control select2" required>
                                            @foreach ($branches as $index => $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ old('branch_id', $product->branch_id) == $branch->id ? 'selected' : '' }}>
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
                                                <tbody>
                                                    @foreach ($product->items as $item)
                                                        <input type="hidden" name="item_ids[]"
                                                            value="{{ $item->id }}">

                                                        <tr>
                                                            <td>
                                                                <input type="text" name="sizes[]" class="form-control"
                                                                    value="{{ $item->size }}">
                                                            </td>
                                                            <td><input type="text" name="colors[]" class="form-control"
                                                                    value="{{ $item->color }}" required></td>
                                                            <td><input type="number" min="0" name="prices[]"
                                                                    class="form-control" value="{{ $item->price }}"
                                                                    required>
                                                            </td>
                                                            <td><input type="number" min="0" name="sell_prices[]"
                                                                    class="form-control" value="{{ $item->sell_price }}"
                                                                    required></td>
                                                            <td><input type="number" min="0" name="sell_prices2[]"
                                                                    class="form-control" value="{{ $item->sell_price2 }}"
                                                                    required></td>
                                                            <td><input type="number" min="1" name="qtys[]"
                                                                    class="form-control" value="{{ $item->qty }}"
                                                                    required>
                                                            </td>
                                                            <td><input type="number" min="1" name="min_qtys[]"
                                                                    class="form-control" value="{{ $item->min_qty }}"
                                                                    required>

                                                            </td>
                                                            <td>
                                                                <input type="file" name="photo[]" class="form-control"
                                                                    accept="image/*">
                                                                @if ($item->photo != null && file_exists('images/' . $item->photo))
                                                                    <div style="margin-top:5px;   ">


                                                                        <center>
                                                                            <a href="{{ asset($item->photo ? 'images/' . $item->photo : 'images/no-image.png') }}"
                                                                                target="_blank">
                                                                                <img src="{{ asset($item->photo ? 'images/' . $item->photo : 'images/no-image.png') }}"
                                                                                    alt="Product Image"
                                                                                    style="width: 60px; height: 60px; object-fit: cover; border:1px solid #ccc;">
                                                                            </a>
                                                                        </center>

                                                                    </div>
                                                                @endif
                                                            </td>

                                                            <td><button type="button"
                                                                    class="btn btn-danger btn-sm removeRow">
                                                                    <i class="bx bxs-trash"></i></button></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>


                                        </div>
                                        <button type="button" class="btn btn-primary   mt-4"
                                            id="addRow">{!! __('admin.Add Product') !!}</button>
                                    </div>

                                    {{-- Submit --}}
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

    {{-- JS for adding/removing rows --}}
@section('footer')
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
@endsection

@endsection
