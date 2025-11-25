@php
    use App\Models\Setting;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>طباعة باركود</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            direction: rtl;
        }

        /* ملصق 50x30 مم */
        .label {
            width: 38mm;
            height: 25mm;
            display: inline-flex;
            flex-direction: column;
            justify-content: center;
            /* مركزي عمودي */
            align-items: center;
            /* مركزي أفقي */
            text-align: center;


            border: 1px dashed #ccc;
            box-sizing: border-box;
        }

        .name {
            font-size: 8px;
            font-weight: bold;

            white-space: normal;
            overflow: hidden;
        }

        .barcode {
            margin: 1px 0;
            width: 100%;
            padding: 0 4px 0 9px;

        }

        @media print {
            .label {
                border: none !important;
            }

            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    @foreach ($products as $product)
        @php
            $qty = request()->input('qty.' . $product->id, 1);
        @endphp

        @for ($i = 0; $i < $qty; $i++)
            <div class="label">
                {{-- اسم الشركة --}}
                <div class="name">{{ Setting::find(1)->name }}</div>

                {{-- الباركود --}}
                <div class="barcode">
                    {!! DNS1D::getBarcodeHTML($product->barcode ?? '---', 'C128', 2, 25) !!}
                </div>

                {{-- كود المنتج --}}
                <div class="name">
                    {{ ($product->barcode ?? '---') .
                        ' / ' .
                        (App\Models\SupplierInvoiceItem::where('product_item_id', $product->id)->first()->id ?? 0) .
                        ' / ' .
                        ($product->size ?? '---') }}
                </div>

                {{-- اسم المنتج --}}
                <div class="name" style="  padding:0 3px 0 5px;">{{ $product->product->name ?? '---' }}</div>

                {{-- السعر --}}
                <div style="font-size: 11px;">Price: {{ $product->sell_price ?? '000000' }}</div>
            </div>
        @endfor
    @endforeach
    <script>
        // window.onload = function() {
        //     // يتم استدعاء الطباعة مباشرة بعد تحميل الصفحة
        //     window.print();
        //     // بعد الطباعة أو الإلغاء، يتم العودة إلى الصفحة السابقة
        //     window.onafterprint = () => {
        //         window.location.href = "{{ url()->previous() }}";
        //     };
        // };
    </script>
</body>

</html>
