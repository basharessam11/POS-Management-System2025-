@extends('admin.layout.app')

@section('page', __('admin.Monthly Income Report'))

@section('contant')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Order List Widget -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row gy-4 gy-sm-1">






                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                <div>
                                    <h3 class="mb-2"> {{ number_format($today2, 0, '.', ',') }}</h3>
                                    <p class="mb-0">{{ __('admin.Total Expenses Today') }}</p>
                                    {{-- <p class="mb-0">{!! __('admin.Today') !!}</p> --}}
                                </div>
                                <div class="avatar me-sm-4">
                                    <span class="avatar-initial rounded bg-label-primary ">
                                        <i class="bx bx-wallet bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>







                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                                    <h3 class="mb-2">EGP {{ number_format($today, 0, '.', ',') }} </h3>

                                    <p class="mb-0">{{ __('admin.Total Income Today') }}</p>
                                    {{-- <p class="mb-0">{!! __('admin.Total') !!}</p> --}}
                                </div>
                                <div class="avatar me-sm-4 ">
                                    <span class="avatar-initial rounded bg-label-success ">
                                        <i class="bx bx-wallet bx-sm "></i>
                                    </span>
                                </div>
                            </div>
                        </div>











                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                                <div>
                                    <h3 class="mb-2">{{ number_format($yearly2, 0, '.', ',') }} </h3>
                                    <p class="mb-0">{{ __('admin.Total Annual Expenses') }}</p>
                                    {{-- <p class="mb-0">{!! __('admin.Monthly') !!}</p> --}}
                                </div>
                                <div class="avatar me-lg-4">
                                    <span class="avatar-initial rounded bg-label-danger ">
                                        <i class="bx bx-wallet bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                                <div>
                                    <h3 class="mb-2">{{ number_format($yearly, 0, '.', ',') }} </h3>
                                    <p class="mb-0">{{ __('admin.Total Annual Income') }}</p>
                                    {{-- <p class="mb-0">{!! __('admin.Monthly') !!}</p> --}}
                                </div>
                                <div class="avatar me-lg-4">
                                    <span class="avatar-initial rounded bg-label-warning  ">
                                        <i class="bx bx-wallet bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h5>{{ __('admin.Total income for each month of the year') }} {{ $year }}</h5>
                </div>

                <div class="card-body">


                    <div id="products-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="card-header d-flex border-top rounded-0 flex-wrap py-md-0">






                            <form method="GET" action="{{ route('report.income') }}">
                                <div class="row g-3 mb-12 mb-3">
                                    <div class="col-md-8">
                                        <label>{{ __('admin.Select Year') }}</label>
                                        <input type="number" name="year" class="form-control"
                                            value="{{ $year }}">
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-2 d-flex align-items-end">
                                        <button type="submit"
                                            class="btn btn-primary w-100 mt-4">{!! __('admin.Submit') !!}</button>
                                    </div>


                                </div>
                            </form>

                            {{-- --------------------------------------------------------------End button-------------------------------------------------------------------- --}}



                            <div class="d-flex justify-content-start justify-content-md-end align-items-baseline">
                                <div
                                    class="dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0">

                                    <div class="dt-buttons btn-group flex-wrap">
                                        <!-- زر الطباعة -->
                                        <button class="btn btn-primary mb-3 mt-3" onclick="printProductsTable()">
                                            🖨️ {{ __('admin.Print Table') }}
                                        </button>


                                    </div>
                                </div>
                            </div>
                            {{-- --------------------------------------------------------------End button-------------------------------------------------------------------- --}}


                        </div>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="min-width: 60px;">{{ __('admin.Month') }}</th>
                                    <th style="min-width: 170px;">{{ __('admin.Total Sales (EGP)') }}</th>
                                    <th style="min-width: 170px;">{{ __('admin.Total Expenses (EGP)') }}</th>
                                    <th style="min-width: 170px;">{{ __('admin.Total Income (EGP)') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($months as $month => $total)
                                    <tr>
                                        @php
                                            $arabicMonths = [
                                                1 => __('admin.January'),
                                                2 => __('admin.February'),
                                                3 => __('admin.March'),
                                                4 => __('admin.April'),
                                                5 => __('admin.May'),
                                                6 => __('admin.June'),
                                                7 => __('admin.July'),
                                                8 => __('admin.August'),
                                                9 => __('admin.September'),
                                                10 => __('admin.October'),
                                                11 => __('admin.November'),
                                                12 => __('admin.December'),
                                            ];
                                        @endphp

                                        <td>{{ $arabicMonths[$month] }}</td>

                                        <td>
                                            <div class="alert alert-primary">
                                                {{ number_format($total, 2) }}
                                            </div>
                                        </td>

                                        <td>
                                            <div class="alert alert-danger">
                                                {{ number_format($expanses[$month], 2) }}
                                            </div>
                                        </td>

                                        <td>
                                            <div class="alert alert-success">
                                                {{ number_format($income[$month] - $expanses[$month], 2) }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>

        </div>
    </div>
@endsection
