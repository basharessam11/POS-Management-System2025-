@php
    use App\Models\Setting;

    // دالة تحويل الأرقام
    function arabic_numbers($string)
    {
        $western_arabic = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $eastern_arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        return str_replace($western_arabic, $eastern_arabic, $string);
    }

    // 💡 حسابات جديدة للصافي والكمية
    $invoice_total_after_discount = $invoice->total - $invoice->discount;
    $return_total =
        isset($invoice->returnItems) && $invoice->returnItems->count() > 0 ? $invoice->returnItems->sum('total') : 0;
    $final_net_total = $invoice_total_after_discount - $return_total;
    $total_sold_qty = $invoice->items->sum('qty');
    $total_return_qty =
        isset($invoice->returnItems) && $invoice->returnItems->count() > 0 ? $invoice->returnItems->sum('qty') : 0;
    $net_qty = $total_sold_qty;

    // تفاصيل التاريخ
    $hour = $invoice->created_at->format('H'); // ساعة 24
    $am_pm = $hour < 12 ? 'ص' : 'م'; // ص = صباحا، م = مساءً
    $created_at = $invoice->created_at->format('Y/m/d h:i'); // صيغة 12 ساعة
    $created_at_ar = arabic_numbers($created_at) . " $am_pm";

    // حسابات الأرصدة (لجزء الأرصدة المتبقي الذي لم يتم إرساله، سأضيف حساباته هنا بناءً على المنطق المعتاد)
    $paid_amount = $invoice->paid ?? 0;
    $previous_balance = ($invoice->supplier->total ?? 0) - ($invoice->remaining ?? 0);
    $current_balance = $previous_balance + $final_net_total - $paid_amount;

@endphp
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>فاتورة بيع - {{ Setting::find(1)->name }}</title>
    <style>
        @page {
            size: 68mm 297mm;
            margin: 0;
        }

        body {
            width: 65mm;
            margin: 0 auto;
            padding: 2mm;
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            direction: rtl;
            text-align: right;
            font-weight: bold;

        }

        /* (CSS Styles remain the same) */
        @font-face {
            font-family: 'Arial';
            src: url('{{ storage_path('app/fonts/alfont_com_arial-1.ttf') }}') format('truetype');
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
            font-weight: bold;
        }

        .table thead th {
            font-size: 11px;
            background-color: #d3d3d3;
            font-weight: bold;
        }

        .return-table thead th {
            background-color: #ffcccc;
            color: #d80000;
            border-color: #d80000;
        }

        /* تنسيق لصف الإجمالي الجديد */
        .final-summary {
            background-color: #e0f7fa;
            /* لون فاتح مميز */
            color: #000000;
            /* نص أخضر داكن */
            font-size: 11px;
        }
    </style>

</head>

<body>
    <div style="display: flex; justify-content: space-between; width: 100%;">
        <div class="right" style="width: 70%;">
            {{ Setting::find(1)->name }}
            {{ Setting::find(1)->address }}<br>
            هاتف: {{ Setting::find(1)->phone }}<br>
            العنوان: {{ Setting::find(1)->location }}<br>

            تاريخ: {{ $created_at_ar }}<br>
            رقم الفاتورة: {{ arabic_numbers($invoice->id) }}
            <br>
            اسم المورد: {{ $invoice->supplier->name ?? 'غير محدد' }}


        </div>
        <div class="left" style="width: 30%; text-align: left;">
            <img style="width: 68px; height:auto"
                src="{{ asset('images') }}/{{ Setting::find(1)->photo != null ? Setting::find(1)->photo : 'no-image.png' }}"
                class="ms-auto" alt="logo" />
        </div>
    </div>



    <table class="table">
        <thead>
            <tr class="center">
                <th>م</th>
                <th>المنتج المرتجع</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->returnItems as $key => $returnItem)
                <tr class="center">
                    <td class="text-start"> {{ $key + 1 }}</td>
                    <td> {{ $returnItem->productItem->product->name }}</td>
                    <td>{{ arabic_numbers($returnItem->qty) }}</td>
                    <td>{{ arabic_numbers(number_format($returnItem->price, 0)) }}</td>
                    <td>{{ arabic_numbers(number_format($returnItem->total, 0)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>




    <table class="table" style="margin-top: 10px;">
        <thead>
            <tr class="center">
                <th>الإجمالي المرتجعات</th>



                <th>الكمية </th>
                <th>المدفوع</th>

            </tr>
        </thead>
        <tbody>
            <tr class="center final-summary">
                {{-- إجمالي البيع --}}
                <td> {{ arabic_numbers(number_format($invoice->total, 0)) }}</td>

                <td>{{ arabic_numbers($returnItem->qty) }}</td>
                <td>{{ arabic_numbers(number_format($paid_amount, 0)) }}</td>
                {{-- المدفوع --}}

            </tr>
        </tbody>
    </table>


    <!-- جدول الأرصدة المستقل، بناءً على حسابات PHP الجديدة -->
    <table class="table" style="margin-top: 10px;">
        <thead>
            <tr class="center">

                <th>رصيد سابق</th>
                <th>رصيد حالي</th>
            </tr>
        </thead>
        <tbody>
            <tr class="center">
                <td>{{ arabic_numbers(number_format($invoice->supplier->total + abs($invoice->paid), 0)) }}
                </td>
                <td>{{ arabic_numbers(number_format($invoice->supplier->total, 0)) }}</td>

            </tr>
        </tbody>
    </table>



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
