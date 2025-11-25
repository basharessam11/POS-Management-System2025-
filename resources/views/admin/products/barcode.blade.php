@extends('admin.layout.app')

@section('page', __('admin.category_Management'))

@section('contant')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">{!! __('admin.Print') !!} </h5>
                        </div>
                        <div class="card-body">

                            {{-- ✅ Alerts --}}
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

                            <form action="{{ route('barcode.print') }}" method="POST" target="_blank">
                                @csrf

                                <div class="table-responsive-sm custom-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('admin.Select') }}</th>
                                                <th>{{ __('admin.Product') }}</th>
                                                <th>{{ __('admin.Barcode') }}</th>
                                                <th>{{ __('admin.Quantity') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($products as $product)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="form-check-input" name="product_id[]"
                                                            value="{{ $product->id }}">
                                                    </td>
                                                    <td>{{ $product->product->name . ' / ' . $product->size }}</td>
                                                    <td>{!! DNS1D::getBarcodeHTML($product->barcode ?? '---', 'C128', 2, 25) !!}</td>
                                                    <td>
                                                        <input type="number" name="qty[{{ $product->id }}]"
                                                            value="{{ $product->qty }}" min="1" class="form-control"
                                                            style="width: 80px;">
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>

                                <button class="btn btn-primary mt-3">{{ __('admin.Print') }}</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
