@extends('admin.layout.app')

@section('page', __('admin.Users_Management'))

@section('contant')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">{!! __('admin.Add Employees') !!}</h5>

                            <button type="button" id="clearDraftBtn" class="btn btn-label-secondary">
                                <i class='bx bx-trash'></i> {{ __('admin.Clear Form') }}
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

                            {{-- ✅ Form - تمت إضافة id للنموذج --}}
                            <form id="userForm" action="{{ route('users.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-3 g-3">
                                    {{-- Name --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Name') !!}</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ old('name') }}" required>
                                    </div>

                                    {{-- Phone --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Phone') !!}</label>
                                        <input type="text" class="form-control" name="phone" id="phone"
                                            value="{{ old('phone') }}">
                                    </div>

                                    {{-- Country --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Country') !!}</label>
                                        <select name="country_id" id="country_id" class="form-select select2">
                                            @foreach ($countries as $c)
                                                <option value="{{ $c->id }}" @selected(old('country_id') == $c->id)>
                                                    {{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Join Date --}}
                                    @php
                                        use Carbon\Carbon;
                                    @endphp

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Join_Date') !!}</label>
                                        <input type="date" class="form-control" name="join_date" id="join_date"
                                            value="{{ old('join_date', Carbon::now()->format('Y-m-d')) }}">
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Status') !!}</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="1" @selected(old('status') == 1)>{!! __('admin.Active') !!}
                                            </option>
                                            <option value="0" @selected(old('status') == 0)>{!! __('admin.Inactive') !!}
                                            </option>
                                        </select>
                                    </div>

                                    {{-- Salary --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Salary') !!}</label>
                                        <input type="text" class="form-control" name="salary" id="salary"
                                            value="{{ old('salary') }}">
                                    </div>

                                    {{-- Email --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Email') !!}</label>
                                        <input type="email" class="form-control" name="email" id="email"
                                            value="{{ old('email') }}">
                                    </div>

                                    {{-- Password --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Password') !!}</label>
                                        <input type="password" class="form-control" name="password" id="password"
                                            placeholder="******" required>
                                    </div>

                                    {{-- Role --}}
                                    <div class="col-12 col-md-12">
                                        <label class="form-label">{!! __('admin.Roles') !!}</label>
                                        <select name="role" id="role" class="form-select select2">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}" @selected(old('role') == $role->name)>
                                                    {{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Photo --}}
                                    <div class="col-12">
                                        <label class="form-label">{!! __('admin.Photo') !!}</label>
                                        {{-- تم استثناء حقل الملفات من الكاش --}}
                                        <input type="file" class="form-control" name="photo">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-3">
                                    {{-- زر مسح المسودة/الكاش --}}

                                    <button type="submit" class="btn btn-primary">{!! __('admin.Save') !!}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script for Local Storage Caching (Form Draft Persistence) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('userForm');
            // مفتاح فريد لبيانات هذا النموذج في التخزين المحلي
            const cacheKey = 'employee_user_draft';

            // العناصر التي سيتم تخزينها مؤقتاً (باستثناء الملفات وكلمة المرور)
            const inputsToCache = [
                'name', 'phone', 'country_id', 'join_date', 'status', 'salary', 'email', 'role'
            ];

            const clearDraftBtn = document.getElementById('clearDraftBtn'); // الزر الجديد

            // 1. دالة لحفظ البيانات إلى localStorage
            function saveDraft() {
                const data = {};
                inputsToCache.forEach(id => {
                    const input = form.elements[id];
                    if (input) {
                        data[id] = input.value;
                    }
                });
                try {
                    localStorage.setItem(cacheKey, JSON.stringify(data));
                } catch (e) {
                    console.error('Failed to save draft to localStorage:', e);
                }
            }

            // 2. دالة لتحميل البيانات من localStorage وتعبئة النموذج
            function loadDraft() {
                // نتحقق أولاً مما إذا كان حقل الاسم يحتوي على قيمة (old() أو قيمة ابتدائية).
                if (form.elements['name'].value) {
                    return;
                }

                const savedData = localStorage.getItem(cacheKey);
                if (savedData) {
                    try {
                        const data = JSON.parse(savedData);

                        inputsToCache.forEach(id => {
                            const input = form.elements[id];
                            if (input && data[id] !== undefined) {
                                input.value = data[id];

                                // إذا كان حقل (Select2) يجب تفعيل حدث change يدوياً
                                if (input.classList.contains('select2') && typeof jQuery !== 'undefined') {
                                    setTimeout(() => jQuery(input).trigger('change'), 50);
                                }
                            }
                        });

                    } catch (e) {
                        console.error('Failed to parse draft from localStorage:', e);
                        localStorage.removeItem(cacheKey); // مسح البيانات التالفة
                    }
                }
            }

            // 3. دالة لمسح الكاش وإعادة تعيين النموذج
            function clearDraftAndResetForm() {
                try {
                    localStorage.removeItem(cacheKey);

                    // إعادة تعيين قيم النموذج إلى الافتراضي/الفارغ
                    form.reset();

                    // تعيين القيمة الافتراضية لتاريخ الانضمام إلى اليوم
                    const joinDateInput = form.elements['join_date'];
                    if (joinDateInput) {
                        joinDateInput.value = new Date().toISOString().split('T')[0];
                    }

                    // إعادة تعيين حقول Select2 يدوياً لضمان تحديث الواجهة
                    inputsToCache.forEach(id => {
                        const input = form.elements[id];
                        if (input && input.classList.contains('select2') && typeof jQuery !== 'undefined') {
                            jQuery(input).trigger('change');
                        }
                    });

                    console.log('Draft cleared and form reset.');

                } catch (e) {
                    console.error('Error clearing draft:', e);
                }
            }


            // 4. تطبيق منطق التحميل والحفظ
            loadDraft();

            // الحفظ التلقائي عند التغيير على الحقول
            form.addEventListener('input', saveDraft);
            form.addEventListener('change', saveDraft); // مهم لحقول select
            form.addEventListener('blur', saveDraft, true);

            // 5. ربط زر مسح المسودة بالوظيفة الجديدة
            clearDraftBtn.addEventListener('click', clearDraftAndResetForm);


            // 6. مسح الكاش عند نجاح عملية الإرسال
            const successAlert = document.querySelector('.alert-success');
            if (successAlert) {
                localStorage.removeItem(cacheKey);
            }
        });
    </script>
@endsection
