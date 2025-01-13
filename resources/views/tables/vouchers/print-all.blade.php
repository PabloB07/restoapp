<head>
    <title>Vouchers Mesa {{ $table->number }}</title>
    <style>
        @page { margin: 0; }
        @media print {
            .no-print { display: none; }
            .page-break { page-break-after: always; }
        }
        /* Rest of your existing CSS styles */
    </style>
</head>
<body>
    @if($hasItems['kitchen'])
        @include('tables.vouchers.partials.kitchen', ['order' => $order])
        <div class="page-break"></div>
    @endif

    @if($hasItems['bar'])
        @include('tables.vouchers.partials.bar', ['order' => $order])
        <div class="page-break"></div>
    @endif

    @if($hasItems['grill'])
        @include('tables.vouchers.partials.grill', ['order' => $order])
    @endif

    <div class="buttons no-print">
        <button onclick="window.print()">Imprimir Todos</button>
        <a href="{{ route(auth()->user()->role . '.tables.show', $table) }}">Volver</a>
    </div>
</body>
