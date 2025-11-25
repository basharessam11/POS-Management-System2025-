@extends('admin.layout.app')

@section('page', __('admin.Users_Management'))

@section('contant')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-4">
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">{!! __('admin.Edit Employees') !!}</h5>
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
                            <form action="{{ route('users.update', $user->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3 g-3">

                                    {{-- Arabic --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Name') !!}</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $user->name) }}" required>
                                    </div>




                                    {{-- Phone --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Phone') !!}</label>
                                        <input type="text" class="form-control" name="phone"
                                            value="{{ old('phone', $user->phone) }}">
                                    </div>

                                    {{-- Country --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Country') !!}</label>
                                        <select name="country_id" class="form-select">
                                            @foreach ($countries as $c)
                                                <option value="{{ $c->id }}" @selected($user->country_id == $c->id)>
                                                    {{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>





                                    {{-- Join Date --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Join_Date') !!}</label>
                                        <input type="date" class="form-control" name="join_date"
                                            value="{{ old('join_date', $user->join_date) }}">
                                    </div>



                                    {{-- Status --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Status') !!}</label>
                                        <select name="status" class="form-select">
                                            <option value="1" @selected($user->status == 1)>{!! __('admin.Active') !!}
                                            </option>
                                            <option value="0" @selected($user->status == 0)>{!! __('admin.Inactive') !!}
                                            </option>
                                        </select>
                                    </div>


                                    {{-- Salary --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Salary') !!}</label>
                                        <input type="text" class="form-control" name="salary"
                                            value="{{ old('salary', $user->salary) }}">
                                    </div>


                                    {{-- Email --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Email') !!}</label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email', $user->email) }}">
                                    </div>

                                    {{-- Password (اختياري للتعديل) --}}
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">{!! __('admin.Password') !!}</label>
                                        <input type="password" class="form-control" name="password" placeholder="******">
                                        <small class="text-muted">اتركه فارغ لو مش عاوز تغيره</small>
                                    </div>



                                    {{-- Role --}}
                                    <div class="col-12 col-md-12">
                                        <label class="form-label">{!! __('admin.Roles') !!}</label>
                                        <select name="role" class="form-select select2">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}"
                                                    {{ $user->roles->contains('id', $role->id) ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Photo --}}
                                    <div class="col-12">
                                        <label class="form-label">{!! __('admin.Photo') !!}</label>
                                        <input type="file" class="form-control" name="photo">
                                        @if ($user->photo)
                                            <div class="mt-2">
                                                <img src="{{ asset('uploads/users/' . $user->photo) }}" alt="user photo"
                                                    width="100" class="rounded">
                                            </div>
                                        @endif
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
