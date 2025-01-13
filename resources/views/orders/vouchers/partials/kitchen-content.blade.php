@extends('layouts.voucher')

@section('title', 'Comanda Cocina #' . $order->id)

@section('content')
<div class="header">
    <div class="title">COMANDA COCINA</div>
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
            <th>Notas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->getKitchenItems() as $item)
        <tr>
            <td style="width: 15%">{{ $item->quantity }}</td>
            <td style="width: 45%">{{ $item->product->name }}</td>
            <td style="width: 40%">{{ $item->notes }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    {{ now()->format('d/m/Y H:i:s') }}
</div>
@endsection
