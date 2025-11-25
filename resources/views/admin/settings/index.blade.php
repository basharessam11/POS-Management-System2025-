@extends('admin.layout.app')

@section('page', 'Order List')


@section('contant')




    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">





            <div class="row g-4">


                @include('admin.layout.menu-slider')
                <!-- Options -->
                <div class="col-12 col-lg-12 pt-4 pt-lg-0">
                    <div class="tab-content p-0">
                        <!-- Store Details Tab -->
                        <div class="tab-pane fade show active" id="store_details" role="tabpanel">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title m-0">{!! __('admin.Settings') !!}</h5>
                                </div>
                                <div class="card-body">
                                    {{-- --------------------------------------------------------------Alert-------------------------------------------------------------------- --}}


                                    @if (session('success'))
                                        <div id="success-message"
                                            class="alert alert-success alert-dismissible fade show text-center"
                                            role="alert">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div id="danger-message"
                                            class="alert alert-danger alert-dismissible fade show text-center"
                                            role="alert">
                                            {{ session('error') }}
                                        </div>
                                    @endif



                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                {{-- @dd($errors) --}}
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    {{-- --------------------------------------------------------------End Alert-------------------------------------------------------------------- --}}


                                    <form action="{{ route('settings.update', 1) }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="row mb-3 g-3">
                                            <!-- Store Name -->
                                            <div class="col-12 col-md-12">
                                                <label class="form-label mb-0"
                                                    for="store-name">{!! __('admin.Store Name') !!}</label>
                                                <input type="text" class="form-control" id="store-name"
                                                    value="{{ $settings->name ?? '' }}" placeholder="Store Name"
                                                    name="name" aria-label="Store Name">
                                            </div>

                                            <!-- Store Phone -->
                                            <div class="col-12 col-md-12">
                                                <label class="form-label mb-0"
                                                    for="store-Phone">{!! __('admin.Phone') !!}</label>
                                                <input type="text" class="form-control" id="store-Phone"
                                                    value="{{ $settings->phone ?? '' }}" placeholder="Store Phone"
                                                    name="phone" aria-label="Store Phone">
                                            </div>

                                            <!-- Store Location -->
                                            <div class="col-12 col-md-12">
                                                <label class="form-label mb-0"
                                                    for="store-Location">{!! __('admin.Location') !!}</label>
                                                <input type="text" class="form-control" id="store-Location"
                                                    value="{{ $settings->location ?? '' }}" placeholder="Store Location"
                                                    name="location" aria-label="Store Location">
                                            </div>

                                            <!-- Photo -->
                                            <div class="col-12 col-md-12">
                                                <label class="form-label mb-0"
                                                    for="photo">{!! __('admin.Photo') !!}</label>
                                                <input type="file" class="form-control" id="photo" name="photo"
                                                    aria-label="Store Photo">
                                                <img style="width: 120px;height:auto"
                                                    src="{{ asset('images') }}/{{ $settings->photo != null ? $settings->photo : 'no-image.png' }}"
                                                    alt="">
                                            </div>

                                            <!-- Save and Discard buttons -->





                                        </div>
                                        <div class="d-flex justify-content-end gap-3">

                                            <button type="submit" class="btn btn-primary">{!! __('admin.Submit') !!}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Options -->





    @endsection

    @section('footer')

        <!-- Page JS -->
        <script src="{{ asset('admin') ?? '' }}/js/app-ecommerce-settings.js"></script>

    @endsection
