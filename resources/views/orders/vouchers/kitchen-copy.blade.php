<title>Orden de Cocina #{{ $order->id }}</title>
    <style>
        @page { margin: 0; }
        @media print {
            .no-print { display: none; }
            body {
                font-family: 'Courier New', Courier, monospace;
                margin: 0;
                padding: 10px;
                font-size: 14px;
            }
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: bold;
        }
        .area-title {
            background: #000;
            color: #fff;
            padding: 5px;
            margin: 10px 0;
            text-align: center;
            font-weight: bold;
        }
        .order-details {
            margin: 10px 0;
            border: 1px solid #000;
            padding: 5px;
        }
        .items {
            width: 100%;
            margin: 10px 0;
        }
        .items th, .items td {
            text-align: left;
            padding: 5px;
        }
        .notes {
            font-style: italic;
            margin-top: 5px;
        }
        .timestamp {
            text-align: right;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    {{-- Kitchen Copy --}}
    <div class="area-title">COCINA</div>
    @include('orders.vouchers.partials.kitchen-content', ['area' => 'kitchen'])

    <div style="page-break-after: always;"></div>

    {{-- Bar Copy --}}
    <div class="area-title">BARRA</div>
    @include('orders.vouchers.partials.kitchen-content', ['area' => 'bar'])

    <div style="page-break-after: always;"></div>

    {{-- Grill Copy --}}
    <div class="area-title">PARRILLA</div>
    @include('orders.vouchers.partials.kitchen-content', ['area' => 'grill'])

    <div class="buttons no-print">
        <button onclick="window.print()">Imprimir</button>
        <a href="{{ route(auth()->user()->role . '.orders.index') }}">Volver</a>
    </div>
</body>
