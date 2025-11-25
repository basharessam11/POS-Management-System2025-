@extends('admin.layout.app')

@section('page', __('admin.Invoice_Management'))

@section('contant')َ
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">



            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">

                        <div class="card-header">
                            <h5 class="card-title m-0">{!! __('admin.Edit') !!} {{ __('admin.Payment') }}</h5>
                        </div>

                        <div class="card-body">

                            {{-- Alerts (رسائل النجاح/الخطأ) --}}
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

                            {{-- نموذج التعديل --}}
                            <form action="{{ route('customer_payments.update', $customerPayment->id) }}" method="POST">
                                @csrf
                                @method('PUT') {{-- مهم جداً لاستخدام دالة update في لارافيل --}}

                                <div class="row g-3">
                                    {{-- حقل العميل (customer_id) --}}
                                    <div class="col-12">
                                        <label class="form-label" for="customer_id">{{ __('admin.Customer') }} <span
                                                class="text-danger">*</span></label>
                                        {{-- يجب أن يتم تمرير متغير $customers من الكنترولر --}}
                                        <select name="customer_id" id="customer_id"
                                            class="form-control select2 @error('customer_id') is-invalid @enderror"
                                            required>

                                            {{-- تحديد العميل الحالي --}}
                                            <option value="{{ $customer->id }}"
                                                {{ old('customer_id', $customerPayment->customer_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} (ID: {{ $customer->id }})
                                            </option>

                                        </select>
                                        @error('customer_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- حقل نوع الدفع (type) --}}
                                    <div class="col-md-6 col-12">
                                        <label class="form-label" for="type">{{ __('admin.Invoice Type') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="type" id="type"
                                            class="form-control @error('type') is-invalid @enderror" required>
                                            {{-- تحديد النوع الحالي بناءً على البيانات المخزنة --}}
                                            <option value="receive"
                                                {{ old('type', $customerPayment->type) == 'receive' ? 'selected' : '' }}>
                                                {{ __('admin.Receive') }}
                                            </option>
                                            <option value="pay"
                                                {{ old('type', $customerPayment->type) == 'pay' ? 'selected' : '' }}>
                                                {{ __('admin.Pay') }}
                                            </option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- حقل المبلغ (amount) --}}
                                    <div class="col-md-6 col-12">
                                        <label class="form-label" for="amount">{{ __('admin.Amount') }} <span
                                                class="text-danger">*</span></label>
                                        {{-- تعبئة القيمة الحالية من $customerPayment->amount --}}
                                        <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                            name="amount" id="amount"
                                            value="{{ old('amount', $customerPayment->amount) }}" step="1"
                                            placeholder="{{ __('admin.Enter Amount') }}" required min="1">
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- حقل الملاحظة (note) --}}
                                    <div class="col-12">
                                        <label class="form-label" for="note">{{ __('admin.Note') }}</label>
                                        {{-- تعبئة القيمة الحالية من $customerPayment->note --}}
                                        <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror"
                                            placeholder="{{ __('admin.Payment notes (optional)') }}">{{ old('note', $customerPayment->note) }}</textarea>
                                        @error('note')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- زر الإرسال --}}
                                    {{-- زر الإرسال --}}
                                    {{-- زر الإرسال --}}
                                    <div class="col-12 mt-4 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('admin.Save') }}
                                        </button>
                                        <a href="{{ route('customer_payments.index', ['customer_id' => $customer->id]) }}"
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
    {{-- تفعيل Select2 لحقل العميل إذا كان مُحمّلاً في الـ Layout --}}
    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('#customer_id').select2({
                    placeholder: "{{ __('admin.Select Customer...') }}",
                    allowClear: true,
                    width: '100%'
                });
            }
        });
    </script>
@endpush
