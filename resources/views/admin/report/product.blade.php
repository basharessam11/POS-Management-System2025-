@extends('admin.layout.app')

@section('page', 'Order List')


@section('contant')




    <!-- Content wrapper -->
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row gy-4 gy-sm-1">






                        <div class="col-sm-6 col-lg-6">
                            <div
                                class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                <div>
                                    <h3 class="mb-2">EGP
                                        @php
                                            $total = DB::table('product_items')
                                                ->selectRaw('SUM(qty * price) as total')
                                                ->value('total');
                                        @endphp

                                        {{ number_format($total, 0, '.', ',') }}

                                    </h3>
                                    <p class="mb-0">{{ __('admin.Total Price of Products') }}</p>
                                    {{-- <p class="mb-0">{!! __('admin.Today') !!}</p> --}}
                                </div>
                                <div class="avatar me-sm-4">
                                    <span class="avatar-initial rounded bg-label-primary ">
                                        <i class="bx bx-wallet bx-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>







                        <div class="col-sm-6 col-lg-6">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                                    <h3 class="mb-2"> @php
                                        $qty = DB::table('product_items')->selectRaw('SUM(qty) as qty')->value('qty');
                                    @endphp


                                        {{ number_format($qty, 0, '.', ',') }}
                                    </h3>

                                    <p class="mb-0">{{ __('admin.Quantity') }}</p>
                                    {{-- <p class="mb-0">{!! __('admin.Total') !!}</p> --}}
                                </div>
                                <div class="avatar me-sm-4 ">
                                    <span class="avatar-initial rounded bg-label-success ">
                                        <i class="bx bx-wallet bx-sm "></i>
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
                    <h5 class="card-title"> {!! __('admin.Products') !!}</h5>
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






                                <form method="GET" action="{{ route('report.product') }}">
                                    <div class="row g-3 align-items-end mb-4">
                                        <!-- البحث -->
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">{!! __('admin.Search') !!}</label>
                                            <input type="search" name="search" value="{{ request('search') }}"
                                                class="form-control" placeholder="{{ __('admin.Search') }}"
                                                aria-controls="products-table">
                                        </div>

                                        <!-- الماركة -->
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">{!! __('admin.Brand') !!}</label>
                                            <select class="form-control select2" name="brand_id">
                                                <option value="all">{!! __('admin.select') !!} {!! __('admin.All') !!}
                                                </option>

                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- زر البحث -->
                                        <div class="col-12 col-md-2">
                                            <button type="submit" class="btn btn-primary w-100 mt-2">
                                                {!! __('admin.Submit') !!}
                                            </button>
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
                        <table id="products-table"
                            class="datatables-products table border-top dataTable no-footer dtr-column">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="min-width: 50px;">{{ __('admin.Barcode') }}</th>
                                    <th style="min-width: 170px;">{{ __('admin.Name') }}</th>
                                    <th style="min-width: 70px;">{{ __('admin.Remaining Quantity') }}</th>
                                    <th style="min-width: 120px;">{{ __('admin.Value') }}</th>





                                </tr>
                            </thead>
                            <tbody>
                                {{-- @dd($expenses) --}}
                                {{-- @if ($expenses->isEmpty())
                                <tr class="odd">
                                    <td valign="top" colspan="6" class="dataTables_empty">لا توجد سجلات مطابقة</td>
                                </tr>
                            @endif --}}

                                @foreach ($products as $index => $product)
                                    <tr class="odd">
                                        <td>{{ $products->firstItem() + $index }}</td>
                                        <td>{{ $product->barcode }}</td>
                                        <td>{{ $product->product->name . ' / ' . $product->product->category->name . ' / ' . $product->product->brand->name . ' / ' . $product->size }}
                                        </td>


                                        <td>

                                            @if ($product->qty <= $product->min_qty)
                                                <div class="alert   alert-danger ">
                                                    {{ $product->qty }}
                                                </div>
                                            @else
                                                <div class="alert  alert-success ">
                                                    {{ $product->qty }}
                                                </div>
                                            @endif

                                        </td>
                                        {{-- @dd( $product->price) --}}


                                        <td>
                                            <div class="alert  alert-primary ">EGP {{ $product->qty * $product->price }}
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <div class="row mx-2">
                            {{ $products->links('vendor.pagination.bootstrap-5') }}
                        </div>

                    </div>
                    <br>
                    <br>
                </div>

            </div>
        </div>
        <!-- / Content -->

        <script>
            function printProductsTable() {
                // الحصول على محتوى الجدول
                const tableContent = document.getElementById('products-table').outerHTML;

                // فتح نافذة جديدة للطباعة
                const printWindow = window.open('', '', 'width=1000,height=700');

                // كتابة المحتوى في صفحة الطباعة
                printWindow.document.write(`
        <html dir="rtl" lang="ar">
        <head>
            <title>{{ __('admin.Print Products Report') }}</title>
            <style>
                body { font-family: 'Arial', sans-serif; direction: rtl; text-align: center; margin: 20px; }
                h2 { margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                th { background-color: #f2f2f2; font-weight: bold; }
                tr:nth-child(even) { background-color: #f9f9f9; }
            </style>
        </head>
        <body>
            <h2>{{ __('admin.Products Report') }}</h2>
            ${tableContent}
        </body>
        </html>
    `);

                // إغلاق المستند وطباعة الصفحة
                printWindow.document.close();
                printWindow.print();
            }
        </script>



    @endsection
