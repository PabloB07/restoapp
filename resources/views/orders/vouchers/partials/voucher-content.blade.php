@extends('layouts.voucher')

@section('title', 'Boleta #' . $order->id)

@section('content')
<div class="header">
    <div class="title">{{ $companySettings->name ?? 'VASTAGO' }}</div>
    @if(isset($companySettings))
        <div>{{ $companySettings->business_line }}</div>
        <div>RUT: {{ $companySettings->rut }}</div>
        <div>{{ $companySettings->address }}</div>
    @endif
</div>

<div class="details">
    <div>Boleta #{{ $order->id }}</div>
    <div>Mesa: {{ $order->table->number }}</div>
    <div>Fecha: {{ $order->created_at->format('d/m/Y H:i') }}</div>
    <div>Atendido por: {{ $order->user->username }}</div>
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
        @foreach($order->orderItems as $item)
        <tr>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->product->name }}</td>
            <td>${{ number_format($item->price, 0, ',', '.') }}</td>
            <td>${{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="text-align: right; margin-top: 20px;">
    <div>Subtotal: ${{ number_format($order->total, 0, ',', '.') }}</div>
    <div>IVA (19%): ${{ number_format($order->total * 0.19, 0, ',', '.') }}</div>
    <div style="border-top: 1px solid #000; margin-top: 5px; padding-top: 5px;">
        <strong>TOTAL: ${{ number_format($order->total * 1.19, 0, ',', '.') }}</strong>
    </div>
</div>

<div class="footer">
    ¡Gracias por su preferencia!
    <br>
    {{ $companySettings->footer_message ?? '' }}
</div>
@endsection
