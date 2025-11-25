@php
    use App\Models\Setting;
@endphp
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>فاتورة بيع - {{ Setting::find(1)->name }}</title>
    <style>
        /* استخدام خط يدعم العربية */
        @font-face {
            font-family: 'Arial';
            src: url('{{ storage_path('app/fonts/alfont_com_arial-1.ttf') }}') format('truetype');
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            direction: rtl;
            /* مهم لجعل النص من اليمين لليسار */
            text-align: right;
            /* محاذاة عامة للنصوص */
            font-weight: bold;
            /* كل النصوص بولد */
            margin-right: 20px;
        }

        .center {
            text-align: center;
            font-weight: bold;
        }

        .left {
            text-align: left;
            font-weight: bold;
        }

        .right {
            text-align: right;
            font-weight: bold;
        }

        .line {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .table td,
        .table th {
            padding: 2px;
            border: 1px solid #000;
            /* لتوضيح الأعمدة عند الطباعة */
            font-weight: bold;
        }

        .table thead th {
            font-size: 14px;
            background-color: #d3d3d3;
            font-weight: bold;
        }
    </style>

</head>

<body>
    <div style="display: flex; justify-content: space-between; width: 100%;">
        <div class="right">
            {{ Setting::find(1)->name }}
            {{ Setting::find(1)->address }}<br>
            هاتف: {{ Setting::find(1)->phone }}<br>
            العنوان: {{ Setting::find(1)->location }}<br>
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
            رقم الفاتورة: {{ arabic_numbers($invoice->id) }}

        </div>
        <div class="left">
            <img style="width: 68px; height:auto"
                src="{{ asset('images') }}/{{ Setting::find(1)->photo != null ? Setting::find(1)->photo : 'no-image.png' }}"
                class="ms-auto" alt="logo" />


        </div>
    </div>

    <table class="table">
        <thead>
            <tr class="center">
                <th>المنتج</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr class="center">
                    <td> {{ $item->productItem->product->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ number_format($item->price, 0) }}</td>
                    <td>{{ number_format($item->total, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="table">
        <thead>
            <tr class="center">
                <th>الإجمالي</th>
                <th>الخصم</th>
                <th>الصافي</th>
                <th>الكمية</th>
                <th>المدفوع</th>



            </tr>
        </thead>
        <tbody>

            <tr class="center">
                <td> {{ number_format($invoice->total, 0) }}</td>
                <td>{{ number_format($invoice->discount, 0) }}</td>
                <td>{{ number_format($invoice->total - $invoice->discount, 0) }}</td>
                <td>{{ $invoice->items->sum('qty') }}</td>
                <td>{{ number_format($invoice->paid, 0) }}</td>

            </tr>

        </tbody>
    </table>
    <div class="center">شكراً لزيارتكم
        <br>
        المرتجع خلال 10 ايام والتبديل خلال 15 يوم بالفاتورة ماعدا رمضان والعيد
    </div>

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
