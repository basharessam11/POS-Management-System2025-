@extends('admin.layout.app')

@section('page', __('admin.Supplier Invoices'))


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
                                    <h3 class="mb-2"> {{ number_format($totalAmount ?? 0, 2) }} </h3>

                                    <p class="mb-0">{{ __('admin.Total Invoices') }}</p>
                                </div>
                                <div class="avatar me-sm-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="bx bx-calendar bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>





                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                <div>
                                    <h3 class="mb-2">{{ number_format($totalReturn ?? 0, 2) }} </h3>
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
                                    <h3 class="mb-2">{{ number_format($monthlyAmount ?? 0, 2) }} </h3>
                                    <p class="mb-0">{{ __('admin.Paid') }}</p>
                                </div>
                                <div class="avatar me-lg-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="bx bx-check-double bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>




                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start   pb-3 pb-sm-0 card-widget-3">
                                <div>
                                    <h3 class="mb-2"> {{ number_format($todayAmount ?? 0, 2) }} </h3>
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">{{ __('admin.Supplier Invoices') }}</h5>

                        @if (request('id'))
                            <div class="d-flex gap-2">
                                <a href="{{ route('supplier_returns.index', ['id' => request('id')]) }}"
                                    class="btn btn-warning">
                                    <i class="bx bx-refresh"></i> {{ __('admin.View Returns') }}
                                </a>

                                <a href="{{ route('supplier_payments.index', ['supplier_id' => request('id')]) }}"
                                    class="btn btn-warning">
                                    <i class="bx bx-refresh"></i> {{ __('admin.View Payments') }}
                                </a>
                            </div>
                        @endif
                    </div>






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



                                <form method="GET" action="{{ route('supplier_invoice.index') }}">
                                    <div class="row g-2 mb-4">
                                        <input type="hidden" name="id" value="{{ $id }}">

                                        <!-- البحث -->
                                        <div class="col-12 col-md-4 d-flex align-items-end">
                                            <input type="search" name="search" value="{{ request('search') }}"
                                                class="form-control" placeholder="{{ __('admin.Search') }}">
                                        </div>

                                        <!-- التاريخ من -->
                                        <div class="col-12 col-md-3">
                                            <label for="date_from" class="form-label">{{ __('admin.From Date') }}</label>
                                            <input type="date" name="date_from" id="date_from"
                                                value="{{ request('date_from') }}" class="form-control">
                                        </div>

                                        <!-- التاريخ إلى -->
                                        <div class="col-12 col-md-3">
                                            <label for="date_to" class="form-label">{{ __('admin.To Date') }}</label>
                                            <input type="date" name="date_to" id="date_to"
                                                value="{{ request('date_to') }}" class="form-control">
                                        </div>

                                        <!-- زر البحث -->
                                        <div class="col-12 col-md-2 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary w-100 mt-3">
                                                {!! __('admin.Submit') !!}
                                            </button>
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

                                            <a href="{{ route('supplier_invoice.create') }}?id={{ $id }}">

                                                <button class="btn btn-secondary add-new btn-primary ms-2" tabindex="0"
                                                    aria-controls="products-table" type="button"
                                                    data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasEcommerceCategoryList"><span><i
                                                            class="bx bx-plus me-0 me-sm-1"></i>{{ __('admin.Add Invoice') }}</span></button>

                                            </a>

                                            <a
                                                href="{{ route('supplier_returns.create', ['supplier_id' => request('id')]) }}">

                                                <button class="btn btn-secondary add-new btn-primary ms-2" tabindex="0"
                                                    aria-controls="products-table" type="button"
                                                    data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasEcommerceCategoryList"><span><i
                                                            class="bx bx-plus me-0 me-sm-1"></i>{{ __('admin.Add Return') }}</span></button>

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
                                    <th>{{ __('admin.Supplier') }}</th>
                                    <th>{{ __('admin.Invoice Date') }}</th>
                                    <th>{{ __('admin.Total') }}</th>
                                    <th>{{ __('admin.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @dd($expenses) --}}
                                {{-- @if ($expenses->isEmpty())
                                <tr class="odd">
                                    <td valign="top" colspan="6" class="dataTables_empty">لا توجد سجلات مطابقة</td>
                                </tr>
                            @endif --}}

                                @foreach ($invoices as $invoice)
                                    <tr class="odd">
                                        <td>
                                            <input type="checkbox" value="{{ $invoice->id }}"
                                                onclick="data('dt-checkboxes')" class="dt-checkboxes form-check-input">

                                        </td>
                                        <td>
                                            <input type="checkbox" value="{{ $invoice->id }}"
                                                onclick="data('dt-checkboxes')" class="dt-checkboxes form-check-input">



                                        <td>{{ $invoice->id }}</td>
                                        <td>{{ $invoice->supplier->name ?? '-' }}</td>
                                        <td>{{ $invoice->date }}</td>
                                        <td>{{ number_format($invoice->total, 2) }}</td>



                                        <td>
                                            <div class="d-inline-block text-nowrap">
                                                <a href="{{ route('supplier_invoice.edit', $invoice->id) }}">
                                                    <button class="btn btn-sm btn-icon" title="{{ __('admin.Edit') }}">
                                                        <i class="bx bx-edit"></i>
                                                    </button>
                                                </a>
                                                <a href="{{ route('supplier_invoice.show', $invoice->id) }}">
                                                    <button class="btn btn-sm btn-icon" title="{{ __('admin.Show') }}">
                                                        <i class="bx bx-show"></i>
                                                    </button>
                                                </a>

                                                <a href="{{ route('supplier_invoice.print2', $invoice->id) }}">
                                                    <button class="btn btn-sm btn-icon" title="{{ __('admin.Print') }}">
                                                        <i class="bx bxs-printer"></i>
                                                    </button>
                                                </a>
                                                <a href="{{ route('supplier_invoice.print3', $invoice->id) }}">
                                                    <button class="btn btn-sm btn-icon" title="{{ __('admin.Print') }}">
                                                        <i class="bx bxs-printer"></i>1
                                                    </button>
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row mx-2">
                            {{ $invoices->links('vendor.pagination.bootstrap-5') }}
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
                                                    return '{{ __('admin.Details of') }} ' + row
                                                        .data()[1];
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
                            <form method="POST" action="{{ route('supplier_invoice.destroy', 0) }}">
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
