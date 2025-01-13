@extends('layouts.print')

@section('content')
@if($hasItems['kitchen'])
    <div class="print-area">
        <div class="text-center mb-4">
            <h2 class="text-xl font-bold">COCINA</h2>
            <p class="text-sm">Mesa {{ $order->table->number }} - {{ $order->table->title }}</p>
            <p class="text-sm">Orden #{{ $order->id }} - {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <table class="w-full">
            <thead>
                <tr>
                    <th>Cant.</th>
                    <th>Producto</th>
                    <th>Notas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->getKitchenItems() as $item)
                    <tr>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->notes }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="page-break-after: always;"></div>
@endif

@if($hasItems['bar'])
    <div class="print-area">
        <div class="text-center mb-4">
            <h2 class="text-xl font-bold">BAR</h2>
            <p class="text-sm">Mesa {{ $order->table->number }} - {{ $order->table->title }}</p>
            <p class="text-sm">Orden #{{ $order->id }} - {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <table class="w-full">
            <thead>
                <tr>
                    <th>Cant.</th>
                    <th>Producto</th>
                    <th>Notas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->getBarItems() as $item)
                    <tr>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->notes }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="page-break-after: always;"></div>
@endif

@if($hasItems['grill'])
    <div class="print-area">
        <div class="text-center mb-4">
            <h2 class="text-xl font-bold">PARRILLA</h2>
            <p class="text-sm">Mesa {{ $order->table->number }} - {{ $order->table->title }}</p>
            <p class="text-sm">Orden #{{ $order->id }} - {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <table class="w-full">
            <thead>
                <tr>
                    <th>Cant.</th>
                    <th>Producto</th>
                    <th>Notas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->getGrillItems() as $item)
                    <tr>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->notes }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="page-break-after: always;"></div>
@endif
@endsection
