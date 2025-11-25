@extends('admin.layout.app')

@section('page', __('admin.Units_Management'))

@section('contant')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">{!! __('admin.Edit') !!} {!! __('admin.Units') !!}</h5>
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
                            <form action="{{ route('units.update', $unit->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3 g-3">

                                    {{-- Name --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Name') !!}</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $unit->name) }}" required>
                                        <br>
                                    </div>

                                    {{-- Manager --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Manager') !!}</label>
                                        <select name="manager_id" class="form-select select2" required>
                                            <option value="">اختر</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('manager_id', $unit->manager_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="submit" class="btn btn-primary">{!! __('admin.Save') !!}</button>
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
