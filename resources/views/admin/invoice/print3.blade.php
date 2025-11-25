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
    $previous_balance = ($invoice->customer->price ?? 0) - ($invoice->remaining ?? 0);
    $current_balance = $previous_balance + $final_net_total - $paid_amount;

@endphp
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>فاتورة بيع - {{ Setting::find(1)->name }}</title>
    <style>
        /* (CSS Styles remain the same) */
        @font-face {
            font-family: 'Arial';
            src: url('{{ storage_path('app/fonts/alfont_com_arial-1.ttf') }}') format('truetype');
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
    @if (session('success'))
        <script>
            localStorage.removeItem('invoice_form_cache');
        </script>
    @endif
    <div style="display: flex; justify-content: space-between; width: 100%;">
        <div class="right" style="width: 70%;">
            {{ Setting::find(1)->name }}
            {{ Setting::find(1)->address }}<br>
            هاتف: {{ Setting::find(1)->phone }}<br>
            العنوان: {{ Setting::find(1)->location }}<br>

            تاريخ: {{ $created_at_ar }}<br>
            رقم الفاتورة: {{ arabic_numbers($invoice->id) }}
            <br>
            اسم العميل: {{ $invoice->customer->name ?? 'غير محدد' }}


        </div>
        <div class="left" style="width: 30%; text-align: left;">
            <img style="width: 68px; height:auto"
                src="{{ asset('images') }}/{{ Setting::find(1)->photo != null ? Setting::find(1)->photo : 'no-image.png' }}"
                class="ms-auto" alt="logo" />
        </div>
    </div>


    <div class="center">
        <h3>منتجات الفاتورة</h3>
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
                    <td>{{ arabic_numbers($item->qty) }}</td>
                    <td>{{ arabic_numbers(number_format($item->price, 0)) }}</td>
                    <td>{{ arabic_numbers(number_format($item->total, 0)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($return_total > 0)
        <div class="center" style="margin-top: 15px;">
            <h3 style="color: #d80000; border-bottom: 2px dashed #d80000; padding-bottom: 5px;">المنتجات المرتجعة
                (استرداد)
            </h3>
        </div>
        <table class="table return-table">
            <thead>
                <tr class="center">
                    <th>المنتج المرتجع</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->returnItems as $returnItem)
                    <tr class="center">
                        <td> {{ $returnItem->productItem->product->name }}</td>
                        <td>{{ arabic_numbers($returnItem->qty) }}</td>
                        <td>{{ arabic_numbers(number_format($returnItem->price, 0)) }}</td>
                        <td>{{ arabic_numbers(number_format($returnItem->total, 0)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif



    <table class="table" style="margin-top: 10px;">
        <thead>
            <tr class="center">
                <th>الإجمالي </th>
                <th>الخصم</th>
                <th> المرتجعات</th>
                <th>الصافي </th>
                <th>الكمية </th>
                <th>المدفوع</th>
            </tr>
        </thead>
        <tbody>
            <tr class="center final-summary">
                {{-- إجمالي البيع --}}
                <td> {{ arabic_numbers(number_format($invoice->total, 0)) }}</td>
                {{-- الخصم --}}
                <td>{{ arabic_numbers(number_format($invoice->discount, 0)) }}</td>
                {{-- إجمالي المرتجعات --}}
                <td>{{ arabic_numbers(number_format($return_total, 0)) }}</td>
                {{-- الصافي الكلي (الإجمالي - الخصم - المرتجعات) --}}
                <td>{{ arabic_numbers(number_format($final_net_total, 0)) }}</td>
                {{-- الكمية الصافية (كمية البيع - كمية المرتجعات) --}}
                <td>{{ arabic_numbers($net_qty) }}</td>
                {{-- المدفوع --}}
                <td>{{ arabic_numbers(number_format($paid_amount, 0)) }}</td>
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
                <td>{{ arabic_numbers(number_format($previous_balance, 0)) }}</td>
                <td>{{ arabic_numbers(number_format($current_balance, 0)) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="center" style="margin-top: 15px;">شكراً لزيارتكم
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
