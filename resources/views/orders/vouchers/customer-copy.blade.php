<head>
    <meta charset="utf-8">
    <title>Boleta #{{ $order->id }}</title>
    <style>
        @page { margin: 0; }
        @media print {
            .no-print { display: none; }
            body {
                font-family: 'Courier New', Courier, monospace;
                margin: 0;
                padding: 10px;
                font-size: 12px;
            }
        }
        .header { text-align: center; margin-bottom: 10px; }
        .copy-type {
            text-align: center;
            border: 1px solid #000;
            padding: 5px;
            margin: 5px 0;
            font-weight: bold;
        }
        .details { margin: 10px 0; }
        .items { width: 100%; margin: 10px 0; }
        .items th, .items td {
            text-align: left;
            padding: 3px;
        }
        .totals { margin-top: 10px; text-align: right; }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    {{-- Cliente Copy --}}
    <div class="copy-type">COPIA CLIENTE</div>
    @include('orders.vouchers.partials.voucher-content')

    <div class="divider"></div>

    {{-- Local Copy --}}
    <div class="copy-type">COPIA LOCAL</div>
    @include('orders.vouchers.partials.voucher-content')

    <div class="buttons no-print">
        <button onclick="window.print()">Imprimir</button>
        <a href="{{ route(auth()->user()->role . '.orders.index') }}">Volver</a>
    </div>
