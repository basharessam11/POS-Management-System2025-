@extends('admin.layout.app')

@section('page', __('admin.Supplier Payment Management'))

@section('contant')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">

                        <div class="card-header">
                            <h5 class="card-title m-0">{!! __('admin.Edit Supplier Payment') !!}: #{{ $supplier_payment->id }}</h5>
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

                            {{-- Edit Form --}}
                            <form action="{{ route('supplier_payments.update', $supplier_payment->id) }}" method="POST">
                                @csrf
                                @method('PUT') {{-- Important for using the update method in Laravel --}}

                                <div class="row g-3">
                                    {{-- Supplier Field (supplier_id) --}}
                                    <div class="col-12">
                                        <label class="form-label" for="supplier_id">{{ __('admin.Supplier') }} <span
                                                class="text-danger">*</span></label>
                                        {{-- The $suppliers variable should be passed from the controller --}}
                                        <select name="supplier_id" id="supplier_id"
                                            class="form-control select2 @error('supplier_id') is-invalid @enderror"
                                            required>
                                            {{-- Select current supplier --}}
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_id', $supplier_payment->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }} (ID: {{ $supplier->id }})
                                            </option>

                                        </select>
                                        @error('supplier_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Payment Type Field (type) --}}
                                    <div class="col-md-6 col-12">
                                        <label class="form-label" for="type">{{ __('admin.Payment Type') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="type" id="type"
                                            class="form-control @error('type') is-invalid @enderror" required>
                                            {{-- Set current type based on stored data --}}
                                            <option value="receive"
                                                {{ old('type', $supplier_payment->type) == 'receive' ? 'selected' : '' }}>
                                                {{ __('admin.Receive') }}
                                            </option>
                                            <option value="pay"
                                                {{ old('type', $supplier_payment->type) == 'pay' ? 'selected' : '' }}>
                                                {{ __('admin.Pay') }}
                                            </option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Amount Field (amount) --}}
                                    <div class="col-md-6 col-12">
                                        <label class="form-label" for="amount">{{ __('admin.Amount Paid') }} <span
                                                class="text-danger">*</span></label>
                                        {{-- Fill current value from $supplier_payment->amount --}}
                                        <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                            name="amount" id="amount"
                                            value="{{ old('amount', $supplier_payment->amount) }}" step="1"
                                            placeholder="{{ __('admin.Amount') }}" required min="1">
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Note Field (note) --}}
                                    <div class="col-12">
                                        <label class="form-label" for="note">{{ __('admin.Notes') }}</label>
                                        {{-- Fill current value from $supplier_payment->note --}}
                                        <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror"
                                            placeholder="{{ __('admin.Notes') }}">{{ old('note', $supplier_payment->note) }}</textarea>
                                        @error('note')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Submit Button --}}
                                    <div class="col-12 mt-4 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('admin.Save') }}
                                        </button>
                                        <a href="{{ route('supplier_payments.index', ['supplier_id' => $supplier->id]) }}"
                                            class="btn btn-outline-secondary">{{ __('admin.Cancel') }}</a>
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

@push('scripts')
    {{-- Enable Select2 for the supplier field if loaded in the Layout --}}
    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('#supplier_id').select2({
                    placeholder: "{{ __('admin.Select Supplier...') }}",
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    </script>
@endpush
