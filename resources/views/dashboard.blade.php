@php
    use App\Models\Setting;
@endphp
@extends('admin.layout.app')

@section('page', 'Order List')


@section('contant')




    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">


            <div class="card" style="padding:40px; text-align:center;">
                <h2 style="font-size:30px; margin-bottom:15px;">
                    {{ __('admin.welcome_dashboard') }}

                </h2>

                <p style="font-size:18px; color:#666; margin-bottom:25px;">
                    {{ __('admin.happy_to_have_you') }}

                </p>

                <img src="{{ asset('images') }}/{{ Setting::find(1)->photo != null ? Setting::find(1)->photo : 'no-image.png' }}"
                    style="max-width:260px; margin:auto;" alt="welcome">
            </div>

        </div>
        <!-- / Content -->



    @endsection
