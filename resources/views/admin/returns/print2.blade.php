@php
    use App\Models\Setting;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة بيع - {{ Setting::find(1)->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #dee2e6;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .invoice-header,
        .invoice-details {
            margin-bottom: 20px;
        }

        .invoice-header h2 {
            font-weight: bold;
            margin-bottom: 0;
        }

        .invoice-details .card {
            background-color: #f1f1f1;
            border: none;
            padding: 15px;
        }

        table th,
        table td {
            text-align: right;
            vertical-align: middle;
        }



        .table thead th {
            font-size: 14px;
            background-color: #d3d3d3;
        }

        .table tbody td {
            font-size: 13px;
        }

        .table-responsive {
            max-height: 500px;
            /* للتحكم في طول الجدول */
            overflow-y: auto;
        }

        @media print {
            body {
                background-color: #fff;
            }

            .invoice-container {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 0;
            }
        }

        table td,
        table th {
            text-align: center !important;
            vertical-align: middle !important;
        }


        .center {
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .right {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="invoice-container">
        <!-- Header -->
        <div class="row invoice-header     mb-2">
            <div style="display: flex; justify-content: space-between; width: 100%;">
                <div class="right">
                    {{ Setting::find(1)->name }}
                    {{ Setting::find(1)->address }}<br>
                    هاتف: {{ Setting::find(1)->phone }}<br>
                    العنوان: {{ Setting::find(1)->location }}<br>

                </div>
                <div class="center">
                    <img style="width: 68px; height:auto"
                        src="{{ asset('images') }}/{{ Setting::find(1)->photo != null ? Setting::find(1)->photo : 'no-image.png' }}"
                        class="ms-auto" alt="logo" />


                </div>
                <div class="left  " style="text-align: right">
                    @php
                        function arabic_numbers($string)
                        {
                            $western_arabic = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                            $eastern_arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
                            return str_replace($western_arabic, $eastern_arabic, $string);
                        }

                        $hour = $invoice->created_at->format('H'); // ساعة 24
                        $am_pm = $hour < 12 ? 'ص' : 'م'; // ص = صباحا، م = مساءً
                        $created_at = $invoice->created_at->format('Y/m/d h:i'); // صيغة 12 ساعة
                        $created_at_ar = arabic_numbers($created_at) . " $am_pm";
                    @endphp

                    تاريخ: {{ $created_at_ar }}<br>
                    رقم الفاتورة: {{ arabic_numbers($invoice->id) }}<br>
                    اسم العميل: {{ $invoice->user->name }}<br>

                </div>
            </div>

        </div>


        <!-- Items Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm te">
                <thead>

                    <tr> <!-- لون رمادي فاتح -->

                        <th>م</th>
                        <th>البيان</th>
                        <th>الكمية</th>

                        <th>السعر</th>
                        <th>المجموع</th>
                    </tr>
                </thead>
                <tbody>

                    <style>
                        .col-item-name {
                            width: 55% !important;
                            white-space: normal !important;
                        }
                    </style>

                    @foreach ($invoice->items as $index => $item)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td class="col-item-name">
                                {{ $item->productItem->product->name }}
                                {{ $item->size ?? '' }}
                                {{ $item->color ?? '' }}
                            </td>

                            <td>{{ $item->qty }}</td>

                            <td>{{ number_format($item->price, 0) }}</td>
                            <td>{{ number_format($item->total, 0) }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <table class="table table-bordered table-hover table-sm">
                <thead>

                    <tr>

                        <th>الإجمالي</th>
                        <th>الخصم</th>
                        <th>الصافي</th>
                        <th>الكمية</th>

                        <th>المدفوع</th>
                        <th>رصيد سابق</th>
                        <th>رصيد حالي</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>


                        <td>{{ number_format($invoice->total, 0) }}</td>
                        <td>{{ number_format($invoice->discount, 0) }}</td>

                        <td>{{ number_format($p1 = $invoice->total - $invoice->discount, 0) }}</td>
                        <td>{{ $invoice->items->sum('qty') }}</td>
                        <td>{{ number_format($p2 = $invoice->paid, 0) }}</td>
                        <td>{{ number_format($t = $invoice->customer->price - $invoice->remaining, 0) }}</td>
                        <td>{{ number_format($t + $p1 - $p2, 0) }}</td>

                    </tr>




                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="row pt-3  ">
            <div class="col-12 text-center text-muted">

                <p>شكراً لزيارتكم
                    <br>
                    المرتجع خلال 10 ايام والتبديل خلال 15 يوم بالفاتورة ماعدا رمضان والعيد
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = () => {
                window.location.href = "{{ url()->previous() }}";
            };
        };
    </script>

</body>

</html>
