@extends('admin.layout.app')

@section('page', __('admin.customer Payment Management'))

@section('contant')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">

                        <div class="card-header">
                            <h5 class="card-title m-0">{!! __('admin.Add Payment') !!}</h5>
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

                            <form action="{{ route('customer_payments.store') }}" method="POST">
                                @csrf

                                <div class="row g-3">
                                    {{-- customer Field (customer_id) --}}
                                    <div class="col-12">
                                        <label class="form-label" for="customer_id">{{ __('admin.Customer') }} <span
                                                class="text-danger">*</span></label>
                                        {{-- The $customers variable should be passed from the controller --}}
                                        <select name="customer_id" id="customer_id"
                                            class="form-control select2 @error('customer_id') is-invalid @enderror"
                                            required>
                                            <option value="">{!! __('admin.select') !!}
                                            </option>

                                            @foreach ($customers as $customer1)
                                                <option value="{{ $customer1->id }}"
                                                    {{ old('customer_id', request('customer_id') ?? null) == $customer1->id ? 'selected' : '' }}>
                                                    {{ $customer1->name }} (ID: {{ $customer1->id }})
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('customer_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Payment Type Field (type) --}}
                                    <div class="col-md-6 col-12">
                                        <label class="form-label" for="type">{{ __('admin.Payment Type') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="type" id="type"
                                            class="form-control @error('type') is-invalid @enderror" required>
                                            <option selected value="receive"
                                                {{ old('type') == 'receive' ? 'selected' : '' }}>
                                                {{ __('admin.Receive') }}
                                            </option>
                                            <option value="pay" {{ old('type') == 'pay' ? 'selected' : '' }}>
                                                {{ __('admin.Pay') }}
                                            </option>


                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- @dd($customer ) --}}
                                    {{-- Amount Field (amount) --}}
                                    <div class="col-md-6 col-12">
                                        <label class="form-label" for="amount">{{ __('admin.Amount Paid') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                            name="amount" id="amount"
                                            value="{{ old('amount', $customer->price ?? 0) }}" step="1"
                                            placeholder="{{ __('admin.Amount Paid') }}" required min="1">
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Note Field (note) --}}
                                    <div class="col-12">
                                        <label class="form-label" for="note">{{ __('admin.Notes') }}</label>
                                        <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror"
                                            placeholder="{{ __('admin.Notes') }}">{{ old('note') }}</textarea>
                                        @error('note')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Submit Button --}}
                                    <div class="col-12 mt-4 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class='bx bx-plus'></i> {{ __('admin.Save') }}
                                        </button>
                                        <a href="{{ route('customer_payments.index', ['customer_id' => $customer->id ?? null]) }}"
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
