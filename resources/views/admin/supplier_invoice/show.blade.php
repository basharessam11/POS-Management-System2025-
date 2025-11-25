@extends('admin.layout.app')

@section('page', __('admin.Supplier Invoice Management'))

@section('contant')

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">

                        <div class="card-header">
                            <h5 class="card-title m-0">{!! __('admin.Show Invoice') !!}</h5>
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

                                    {{-- supplierInvoice Number --}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Invoice Number') }}</label>
                                        <input disabled type="text" class="form-control" name="number"
                                            value="{{ old('number', $supplierInvoice->number) }}" required>
                                    </div>

                                    {{-- Supplier --}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Supplier') }}</label>
                                        {{-- The select is disabled/readonly as the supplier usually cannot be changed after creation --}}
                                        <select name="supplier_id" class="form-control select2" disabled>
                                            <option value="{{ $supplierInvoice->supplier->id }}" selected>
                                                {{ $supplierInvoice->supplier->name }}
                                            </option>
                                        </select>
                                        {{-- Use a hidden input to ensure the ID is still submitted --}}
                                        <input disabled type="hidden" name="supplier_id"
                                            value="{{ $supplierInvoice->supplier->id }}">
                                    </div>

                                    {{-- Date --}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Date') }}</label>
                                        <input disabled type="date" class="form-control"
                                            value="{{ old('date', $supplierInvoice->date) }}" name="date" required>
                                    </div>

                                    {{-- ITEMS TABLE --}}
                                    <div class="col-12 mt-4">
                                        <h6>{{ __('admin.Products') }}</h6>

                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="itemsTable">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;">#</th>
                                                        <th style="width: 15%;">{{ __('admin.Barcode') }}</th>
                                                        <th style="width: 40%;">{{ __('admin.Product') }}</th>
                                                        <th style="width: 15%;">{{ __('admin.Quantity') }}</th>
                                                        <th style="width: 15%;">{{ __('admin.Price') }}</th>
                                                        <th style="width: 15%;">{{ __('admin.Total') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($supplierInvoice->items as $key => $item)
                                                        <tr>
                                                            <td>
                                                                {{ $key + 1 }}
                                                            </td>
                                                            <td>
                                                                <input disabled type="text" class="form-control barcode"
                                                                    value="{{ $item->productItem->barcode ?? '' }}"
                                                                    placeholder="{{ __('admin.Scan Barcode') }}">
                                                            </td>


                                                            <td>
                                                                <select name="product_item_id[]" disabled
                                                                    class="form-control select2 productSelect">
                                                                    <option value="{{ $item->product_item_id }}" selected>
                                                                        {{ $item->productItem->product->name ?? __('admin.No Name') }}
                                                                    </option>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input disabled type="number" name="qty[]"
                                                                    class="form-control qty" min="1"
                                                                    value="{{ $item->qty }}" required>
                                                            </td>

                                                            <td>
                                                                <input disabled type="number" name="price[]"
                                                                    class="form-control price" step="0.01"
                                                                    value="{{ $item->price }}" required>
                                                            </td>

                                                            <td>
                                                                <input disabled type="number" class="form-control rowTotal"
                                                                    readonly value="{{ $item->qty * $item->price }}">
                                                            </td>


                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>


                                    </div>

                                    {{-- TOTALS --}}
                                    <div class="col-12 col-md-6 mt-3">
                                        <label class="form-label">{{ __('admin.Total') }}</label>
                                        {{-- Total (sum of rowTotal) --}}
                                        <input disabled type="number" class="form-control" id="total" name="total"
                                            readonly value="{{ old('total', $supplierInvoice->total) }}">
                                    </div>


                                    <div class="col-12 col-md-6 mt-3" style="display: none">
                                        <label class="form-label">{{ __('admin.Net') }}</label>
                                        {{-- Net (total - discount) --}}
                                        <input disabled type="number" class="form-control net" id="net"
                                            name="net" readonly value="{{ old('net', $supplierInvoice->net) }}">
                                    </div>

                                    <div class="col-12 col-md-6 mt-3">
                                        <label class="form-label">{{ __('admin.Paid') }}</label>
                                        {{-- Paid Amount --}}
                                        <input disabled type="number" class="form-control" id="paid" name="paid"
                                            step="0.01" value="{{ old('paid', $supplierInvoice->paid) }}">
                                    </div>

                                    <div class="col-12 col-md-6 mt-3">
                                        <label class="form-label">{{ __('admin.Remaining') }}</label>
                                        {{-- Remaining (net - paid) --}}
                                        <input disabled type="number" class="form-control" id="remaining" name="remaining"
                                            readonly value="{{ old('remaining', $supplierInvoice->remaining) }}">
                                    </div>

                                    <div class="col-12 col-md-6 mt-3">
                                        <label class="form-label">{{ __('admin.Previous Balance') }}</label>
                                        {{-- Remaining (net - paid) --}}
                                        <input disabled type="number" class="form-control" readonly
                                            value="{{ old('remaining', $supplierInvoice->supplier->total) }}">
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
