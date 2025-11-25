@extends('admin.layout.app')

@section('page', __('admin.Returns List'))


@section('contant')




    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Order List Widget -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                                    <h3 class="mb-2">{{ number_format($totalAmount ?? 0, 2) }}</h3>

                                    <p class="mb-0">{{ __('admin.Total Sales') }}</p>
                                </div>
                                <div class="avatar me-sm-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="bx bx-calendar bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>





                        <div class="col-sm-6 col-lg-2">
                            <div
                                class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                <div>
                                    <h3 class="mb-2">{{ number_format($totalReturn ?? 0, 2) }}</h3>
                                    <p class="mb-0">{{ __('admin.Returns') }}</p>
                                </div>
                                <div class="avatar me-sm-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="bx bx-wallet bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>



                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                                <div>
                                    <h3 class="mb-2">{{ number_format($monthlyAmount ?? 0, 2) }}</h3>
                                    <p class="mb-0">{{ __('admin.Paid') }}</p>
                                </div>
                                <div class="avatar me-lg-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="bx bx-check-double bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>





                        <div class="col-sm-6 col-lg-2">
                            <div
                                class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                <div>
                                    <h3 class="mb-2">{{ $totalAmount - $monthlyAmount }}</h3>
                                    <p class="mb-0">{{ __('admin.Previous Balance') }}</p>
                                </div>
                                <div class="avatar me-sm-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="bx bx-wallet bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-6 col-lg-2">
                            <div
                                class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                <div>
                                    <h3 class="mb-2">{{ number_format($todayAmount ?? 0, 2) }}</h3>
                                    <p class="mb-0">{{ __('admin.Current Balance') }}</p>
                                </div>
                                <div class="avatar me-sm-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="bx bx-wallet bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>

            <!-- Product List Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"> {{ __('admin.Returns') }}</h5>
                    <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">


                        {{-- --------------------------------------------------------------Alert-------------------------------------------------------------------- --}}


                        @if (session('success'))
                            <div id="success-message" class="alert alert-success alert-dismissible fade show text-center"
                                role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div id="danger-message" class="alert alert-danger alert-dismissible fade show text-center"
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


                    </div>

                </div>

                <!-- customers List Table -->
                <div class="card">

                    <div class="card-datatable table-responsive">
                        <div id="products-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="card-header d-flex border-top rounded-0 flex-wrap py-md-0">


                                {{-- -------------------------------------------------------------- Filter-------------------------------------------------------------------- --}}


                                <form method="GET" action="{{ route('returns.index') }}">
                                    @if (request('customer_id'))
                                        <input type="hidden" name="customer_id" value="{{ request('customer_id') }}">
                                    @endif


                                    <div class="row g-2 mb-4">
                                        <!-- البحث -->
                                        <div class="col-12 col-md-6 col-lg-4 d-flex align-items-end">
                                            <input type="search" name="search" value="{{ request('search') }}"
                                                class="form-control"
                                                placeholder="{{ __('admin.Search by customer or invoice number') }}"
                                                aria-controls="products-table">
                                        </div>

                                        <!-- التاريخ من -->
                                        <div class="col-12 col-md-6 col-lg-2">
                                            <label class="form-label">{{ __('admin.From Date') }}</label>
                                            <input type="date" name="from_date" value="{{ request('from_date') }}"
                                                class="form-control">
                                        </div>

                                        <!-- التاريخ إلى -->
                                        <div class="col-12 col-md-6 col-lg-2">
                                            <label class="form-label">{{ __('admin.To Date') }}</label>
                                            <input type="date" name="to_date" value="{{ request('to_date') }}"
                                                class="form-control">
                                        </div>

                                        <!-- النوع -->
                                        <div class="col-md-2">
                                            <label class="form-label">{{ __('admin.Type') }}</label>
                                            <select name="type" class="form-control">
                                                <option value="">{{ __('admin.All Types') }}</option>
                                                <option value="1" {{ request('type') == 1 ? 'selected' : '' }}>
                                                    {{ __('admin.Retail') }}
                                                </option>
                                                <option value="2" {{ request('type') == 2 ? 'selected' : '' }}>
                                                    {{ __('admin.Wholesale') }}
                                                </option>
                                            </select>
                                        </div>

                                        <!-- زر البحث -->
                                        <div class="col-12 col-md-6 col-lg-2 d-flex align-items-end">
                                            <button type="submit"
                                                class="btn btn-primary w-100 mt-4">{!! __('admin.Submit') !!}</button>
                                        </div>
                                    </div>


                                </form>





                                {{-- --------------------------------------------------------------End Filter-------------------------------------------------------------------- --}}







                                {{-- --------------------------------------------------------------End button-------------------------------------------------------------------- --}}



                                <div class="d-flex justify-content-start justify-content-md-end align-items-baseline">
                                    <div
                                        class="dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-3 mb-sm-0">

                                        <div class="dt-buttons btn-group flex-wrap"> <button
                                                class="btn btn-secondary add-new btn-danger de me-3" tabindex="0"
                                                aria-controls="products-table" type="button" data-bs-toggle="modal"
                                                data-bs-target="#basicModal2" style="display:none"><span><i
                                                        class="bx bx-trash"></i><span
                                                        class="d-none d-sm-inline-block">{{ __('admin.Delete') }}
                                                    </span></span></button>

                                            <a
                                                href="{{ route('returns.create', ['customer_id' => request('customer_id')]) }}">
                                                <button class="btn btn-secondary add-new btn-primary ms-2" tabindex="0"
                                                    aria-controls="products-table" type="button"
                                                    data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasEcommerceCategoryList"><span><i
                                                            class="bx bx-plus me-0 me-sm-1"></i>{!! __('admin.Add') !!}
                                                        {!! __('admin.Returns') !!}</span></button>

                                            </a>



                                        </div>
                                    </div>
                                </div>
                                {{-- --------------------------------------------------------------End button-------------------------------------------------------------------- --}}


                            </div>


                        </div>
                        <table id="products-table"
                            class="datatables-products table border-top dataTable no-footer dtr-column">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>{{ __('admin.Invoice Number') }}</th>
                                    <th>{{ __('admin.Customer') }}</th>
                                    <th>{{ __('admin.Total') }}</th>
                                    <th>{{ __('admin.Discount') }}</th>
                                    <th>{{ __('admin.Type') }}</th>
                                    <th>{{ __('admin.Created By') }}</th>
                                    <th>{{ __('admin.Edited By') }}</th>
                                    <th>{{ __('admin.Added Date') }}</th>
                                    <th>{{ __('admin.Actions') }}</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($invoice as $inv)
                                    <tr class="odd">

                                        <td>
                                            <input type="checkbox" value="{{ $inv->id }}"
                                                onclick="data('dt-checkboxes')" class="dt-checkboxes form-check-input">
                                        </td>

                                        <td>
                                            <input type="checkbox" value="{{ $inv->id }}"
                                                onclick="data('dt-checkboxes')" class="dt-checkboxes form-check-input">
                                        </td>

                                        {{-- رقم الفاتورة --}}
                                        <td>{{ $inv->id }}</td>

                                        {{-- العميل --}}
                                        <td>{{ $inv->customer->name ?? '-' }}</td>

                                        {{-- الإجمالي --}}
                                        <td>{{ $inv->total }}</td>

                                        {{-- الخصم --}}
                                        <td>{{ $inv->discount }}</td>

                                        {{-- النوع --}}
                                        <td>
                                            @if ($inv->type == 1)
                                                <span class="badge bg-primary">{{ __('admin.Retail') }}</span>
                                            @elseif($inv->type == 2)
                                                <span class="badge bg-info">{{ __('admin.Wholesale') }}</span>
                                            @else
                                                <span class="badge bg-info">{{ __('admin.Return') }}</span>
                                            @endif
                                        </td>

                                        {{-- أنشأ بواسطة --}}
                                        <td>{{ $inv->creator->name ?? '-' }}</td>

                                        {{-- عدل بواسطة --}}
                                        <td>{{ $inv->editor->name ?? '-' }}</td>
                                        <td>{{ $inv->created_at->format('Y/m/d h:i A') }}</td>

                                        {{-- الأكشن --}}
                                        <td>
                                            <div class="d-inline-block text-nowrap">
                                                <a href="{{ route('returns.edit', $inv->id) }}">
                                                    <button class="btn btn-sm btn-icon" title="{{ __('admin.Edit') }}">
                                                        <i class="bx bx-edit"></i>
                                                    </button>
                                                </a>



                                            </div>
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>
                        <div class="row mx-2">
                            {{ $invoice->links('vendor.pagination.bootstrap-5') }}
                        </div>
                        <script>
                            $(document).ready(function() {
                                var table = $('#products-table').DataTable({
                                    columnDefs: [{
                                            className: "control",
                                            searchable: false,
                                            orderable: false,
                                            responsivePriority: 2,
                                            targets: 0,
                                            render: function(t, e, s, a) {
                                                // console.log(s)
                                                return ""
                                            }

                                        },
                                        {
                                            targets: 1,

                                            checkboxes: {
                                                selectAllRender: '<input type="checkbox" onclick="data1(`all`)" class="all form-check-input">'
                                            },
                                            render: function(t, e, s, a) {
                                                // console.log(s[0])
                                                return s[0];
                                            },
                                            searchable: !1
                                        }
                                    ],


                                    responsive: {
                                        details: {
                                            display: $.fn.dataTable.Responsive.display.modal({
                                                header: function(row) {
                                                    return '{{ __('admin.Details of') }} ' + row.data()[
                                                        1];
                                                }
                                            }),
                                            type: "column",
                                            renderer: function(api, rowIdx, columns) {
                                                var data = $.map(columns, function(col, i) {
                                                    return col.title ?
                                                        `<tr><td><strong>${col.title}:</strong></td><td>${col.data}</td></tr>` :
                                                        '';
                                                }).join('');
                                                return data ? $('<table class="table"/>').append('<tbody>' + data +
                                                    '</tbody>') : false;
                                            }
                                        }
                                    },
                                    paging: false, // 🚫 إيقاف الباجيناشن
                                    info: false, // 🚫 إخفاء "Showing X to Y of Z entries"
                                    ordering: true,
                                    searching: false
                                });
                            });
                        </script>

                    </div>
                    <br>
                    <br>
                </div>

            </div>
        </div>
        <!-- / Content -->


        {{-- -------------------------------------------------------------- Delete-------------------------------------------------------------------- --}}

        <div class="modal fade" id="basicModal2" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1 " data-i18n="{{ __('admin.Delete') }}">
                            {{ __('admin.Delete') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form method="POST" action="{{ route('returns.destroy', 0) }}">
                                @method('delete')
                                @csrf
                                <div id="name" class=" col mb-3">

                                    {{ __('admin.Are you sure you want to delete?') }}

                                </div>
                                <input class="val" type="hidden" name="id">


                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            data-i18n="{{ __('admin.Close') }}">{{ __('admin.Close') }}</button>
                        <button type="submit" class="btn btn-danger"
                            data-i18n="{{ __('admin.Delete') }}">{{ __('admin.Delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- --------------------------------------------------------------end Delete-------------------------------------------------------------------- --}}



    @endsection
