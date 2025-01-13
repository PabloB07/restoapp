@extends('layouts.voucher')

@section('title', ucfirst($area) . ' - Orden #' . $order->id)

@section('content')
<div class="header">
    <div class="title">
        @switch($area)
            @case('kitchen')
                COMANDA COCINA
                @break
            @case('bar')
                COMANDA BAR
                @break
            @case('grill')
                COMANDA PARRILLA
                @break
        @endswitch
    </div>
    <div>{{ $companySettings->name ?? 'VASTAGO' }}</div>
    <div>Mesa: {{ $order->table->title }}</div>
    <div>Orden #{{ $order->id }}</div>
    <div>{{ $order->created_at->format('d/m/Y H:i') }}</div>
    <div>Mesero: {{ $order->user->username }}</div>
</div>

<table class="items-table">
    <thead>
        <tr>
            <th>Cant</th>
            <th>Producto</th>
            <th>Precio</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td style="width: 15%">{{ $item->quantity }}</td>
            <td style="width: 35%">{{ $item->product->name }}</td>
            <td style="width: 25%">${{ number_format($item->price, 0, ',', '.') }}</td>
            <td style="width: 25%">${{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="totals">
    <div class="subtotal">
        <span>Subtotal:</span>
        <span>${{ number_format($order->total, 0, ',', '.') }}</span>
    </div>
    <div class="tax">
        <span>IVA (19%):</span>
        <span>${{ number_format($order->total * 0.19, 0, ',', '.') }}</span>
    </div>
    @if($order->tip)
        <div class="tip {{ $order->tip->is_accepted ? 'accepted' : '' }}">
            <span>Propina sugerida ({{ $order->tip->percentage }}%):</span>
            <span>${{ number_format($order->tip->amount, 0, ',', '.') }}</span>
        </div>
    @endif
    <div class="total">
        <span>Total:</span>
        <span>${{ number_format($order->total_with_tax_and_tip, 0, ',', '.') }}</span>
    </div>
</div>

<div class="footer">
    <div>Impreso: {{ now()->format('d/m/Y H:i:s') }}</div>
    @if($companySettings)
        <div>{{ $companySettings->footer_message }}</div>
    @endif
</div>
@endsection
