@extends('admin.layout.app')

@section('page', __('admin.Supplier_Management'))

@section('contant')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row g-4">
                <div class="col-12 col-lg-12">
                    <div class="card mb-4">

                        <div class="card-header">
                            <h5 class="card-title m-0">
                                {!! __('admin.Edit') !!} {{ __('admin.Supplier') }}
                            </h5>
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
                                    <ul class="m-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- FORM --}}
                            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3 g-3">

                                    {{-- Name --}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Name') }}</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name', $supplier->name) }}" required>
                                    </div>

                                    {{-- Phone --}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Phone') }}</label>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ old('phone', $supplier->phone) }}" required>
                                    </div>

                                    {{-- Address --}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Address') }}</label>
                                        <input type="text" name="address" class="form-control"
                                            value="{{ old('address', $supplier->address) }}" required>
                                    </div>

                                </div>

                                <div class="d-flex justify-content-end gap-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('admin.Save') }}
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
