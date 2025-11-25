@extends('admin.layout.app')

@section('page', __('admin.Customer_Management'))

@section('contant')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">{!! __('admin.Add') !!} {!! __('admin.Customer') !!}</h5>
                            <button type="button" class="btn btn-label-secondary" id="clearCustomerCache">
                                {!! __('admin.Clear Form') !!}
                            </button>
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

                            {{-- ✅ Form --}}
                            <form action="{{ route('customer.store') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-3 g-3">

                                    {{-- Name --}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Name') }}</label>
                                        <input type="text" name="name" class="form-control" required
                                            value="{{ old('name') }}">
                                    </div>

                                    {{-- Phone --}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Phone') }}</label>
                                        <input type="text" name="phone" class="form-control" required
                                            value="{{ old('phone') }}">
                                    </div>

                                    {{-- Address --}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">{{ __('admin.Address') }}</label>
                                        <input type="text" name="address" class="form-control" required
                                            value="{{ old('address') }}">
                                    </div>

                                </div>





                                <div class="d-flex justify-content-end gap-3">

                                    <button type="submit" class="btn btn-primary">{!! __('admin.Save') !!}</button>
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

            // --- 1. ثوابت و علامات ---
            const CACHE_KEY = 'customer_form_cache'; // مفتاح كاش خاص بصفحة العميل
            let isInitializing = true; // لمنع الحفظ أثناء تحميل البيانات

            // ** مسح الكاش عند الإرسال الناجح (عن طريق رسالة النجاح من الخادم) **
            @if (session('success'))
                localStorage.removeItem(CACHE_KEY);
                console.log('✅ تم مسح كاش نموذج العميل بنجاح بعد إرسال النموذج.');
            @endif


            // --- 2. دالة الحفظ التلقائي للكاش ---
            function saveFormData() {
                if (isInitializing) return;

                const formData = {
                    name: $('[name="name"]').val(),
                    phone: $('[name="phone"]').val(),
                    address: $('[name="address"]').val()
                };

                localStorage.setItem(CACHE_KEY, JSON.stringify(formData));
            }

            // --- 3. دالة تحميل البيانات من الكاش ---
            function loadFormData() {
                const cachedData = localStorage.getItem(CACHE_KEY);
                if (!cachedData) return;

                const formData = JSON.parse(cachedData);

                // استعادة البيانات للحقول الثلاثة
                if (formData.name) {
                    $('[name="name"]').val(formData.name);
                }
                if (formData.phone) {
                    $('[name="phone"]').val(formData.phone);
                }
                if (formData.address) {
                    $('[name="address"]').val(formData.address);
                }
            }

            // --- 4. دالة مسح الكاش يدوياً ---
            function clearFormData() {
                localStorage.removeItem(CACHE_KEY);
                alert('{{ __('admin.Are you sure you want to delete all saved data and start from scratch?') }}');
                // إعادة تحميل الصفحة لرؤية النموذج فارغاً
                window.location.reload();
            }

            // --- 5. الإعداد الأولي ---

            // تحميل البيانات من الكاش عند بدء تشغيل الصفحة
            loadFormData();

            // السماح بالحفظ التلقائي بعد انتهاء عملية التهيئة
            isInitializing = false;

            // ربط زر مسح الكاش (Clear Cache Button)

            // يتم إضافة الزر بجوار زر "حفظ"

            $('#clearCustomerCache').on('click', clearFormData);

            // ربط الحفظ التلقائي بالأحداث (عند الكتابة أو تغيير الحقول)
            $('form').on('input change', '[name="name"], [name="phone"], [name="address"]', function() {
                saveFormData();
            });

        });
    </script>
@endsection
