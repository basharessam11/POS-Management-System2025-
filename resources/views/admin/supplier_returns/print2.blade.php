@php
    use App\Models\Setting;

    // دالة تحويل الأرقام (تم توحيدها في البلوك العلوي)
    function arabic_numbers($string)
    {
        $western_arabic = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $eastern_arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        return str_replace($western_arabic, $eastern_arabic, $string);
    }

    // تفاصيل التاريخ
    $hour = $invoice->created_at->format('H'); // ساعة 24
    $am_pm = $hour < 12 ? 'ص' : 'م'; // ص = صباحا، م = مساءً
    $created_at = $invoice->created_at->format('Y/m/d h:i'); // صيغة 12 ساعة
    $created_at_ar = arabic_numbers($created_at) . " $am_pm";

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

        /* تثبيت الخطوط للأرقام العربية في الطباعة */
        @font-face {
            font-family: 'Arial';
            /* يرجى التأكد من مسار الخط الصحيح على السيرفر */
            src: url('{{ storage_path('app/fonts/alfont_com_arial-1.ttf') }}') format('truetype');
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

        .table thead th {
            font-size: 14px;
            background-color: #d3d3d3 !important;
            color: #333;
            border-color: #aaa !important;
        }

        /* تنسيق لجدول المرتجعات */
        .return-table thead th {
            background-color: #ffdddd !important;
            /* لون فاتح أحمر */
            color: #cc0000;
            border-color: #cc0000 !important;
        }

        /* تنسيق لصف الإجمالي الجديد */
        .final-summary td {
            background-color: #e0f7fa !important;
            font-size: 13px;
            font-weight: bold;
        }

        .table td,
        .table th {
            text-align: center !important;
            vertical-align: middle !important;
        }

        /* تنسيق خاص لجدول الأرصدة */
        .balance-table th {
            border-top: none !important;
        }

        @media print {
            body {
                background-color: #fff;
                margin: 0;
            }

            .invoice-container {
                box-shadow: none;
                border: none;
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <div class="invoice-container">
        <!-- Header -->
        <div class="row invoice-header mb-2">
            <div style="display: flex; justify-content: space-between; width: 100%;"
                class="d-flex justify-content-between align-items-start">
                <div class="right text-start">
                    <p class="mb-1 fw-bold">{{ Setting::find(1)->name }}</p>
                    <p class="mb-1">{{ Setting::find(1)->address }}</p>
                    <p class="mb-1">هاتف: {{ arabic_numbers(Setting::find(1)->phone) }}</p>
                    <p class="mb-1">العنوان: {{ Setting::find(1)->location }}</p>
                </div>
                <div class="center text-center">
                    <img style="width: 68px; height:auto"
                        src="{{ asset('images') }}/{{ Setting::find(1)->photo != null ? Setting::find(1)->photo : 'no-image.png' }}"
                        class="ms-auto" alt="logo"
                        onerror="this.onerror=null; this.src='https://placehold.co/68x68/CCCCCC/333333?text=Logo';" />
                </div>
                <div class="left text-end">
                    <p class="mb-1">تاريخ: {{ $created_at_ar }}</p>
                    <p class="mb-1">رقم الفاتورة: {{ arabic_numbers($invoice->id) }}</p>
                    <p class="mb-1">اسم المورد: {{ $invoice->supplier->name }}</p>
                </div>
            </div>

        </div>


        <!-- Items Table -->
        <div class="table-responsive ">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>م</th>
                        <th>المنتج المرتجع</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->returnItems as $key => $returnItem)
                        {{-- @dd($returnItem->total) --}}
                        <tr>
                            <td class="text-start"> {{ $key + 1 }}</td>
                            <td class="text-start"> {{ $returnItem->productItem->product->name }}</td>
                            <td>{{ arabic_numbers($returnItem->qty) }}</td>
                            <td>{{ arabic_numbers(number_format($returnItem->price, 0)) }}</td>
                            <td>{{ arabic_numbers(number_format($returnItem->total, 0)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>




        <!-- Summary and Balance Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>

                        <th>إجمالي المرتجعات</th>

                        <th>الكمية الصافية</th>
                        <th>المدفوع</th>
                        <th class="bg-secondary text-black">الرصيد السابق</th>
                        <th class="bg-primary  ">الرصيد الحالي</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="final-summary">

                        {{-- إجمالي المرتجعات --}}
                        <td>{{ arabic_numbers(number_format($invoice->total, 0)) }}</td>

                        {{-- الكمية الصافية --}}
                        <td>{{ arabic_numbers($invoice->returnItems->sum('qty')) }}</td>
                        {{-- المدفوع --}}
                        <td>{{ arabic_numbers(number_format($invoice->paid, 0)) }}</td>

                        <td>{{ arabic_numbers(number_format($invoice->supplier->total + abs($invoice->paid), 0)) }}
                        </td>
                        <td>{{ arabic_numbers(number_format($invoice->supplier->total, 0)) }}</td>

                    </tr>
                </tbody>
            </table>


        </div>



    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        window.onload = function() {
            // يتم استدعاء الطباعة مباشرة بعد تحميل الصفحة
            window.print();
            // بعد الطباعة أو الإلغاء، يتم العودة إلى الصفحة السابقة
            window.onafterprint = () => {
                window.location.href = "{{ url()->previous() }}";
            };
        };
    </script>

</body>

</html>
