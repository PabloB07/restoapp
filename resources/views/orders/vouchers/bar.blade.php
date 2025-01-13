@extends('layouts.print')

@section('content')
<div class="print-area">
    <div class="text-center mb-4">
        <h2 class="text-xl font-bold">BARRA</h2>
        <p class="text-sm">Mesa {{ $order->table->number }} - {{ $order->table->title }}</p>
        <p class="text-sm">Orden #{{ $order->id }} - {{ $order->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <table class="w-full">
        <thead>
            <tr>
                <th class="border-b-2 text-left">Cant.</th>
                <th class="border-b-2 text-left">Producto</th>
                <th class="border-b-2 text-left">Notas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->getBarItems() as $item)
                <tr>
                    <td class="py-2">{{ $item->quantity }}</td>
                    <td class="py-2">{{ $item->product->name }}</td>
                    <td class="py-2 text-sm">{{ $item->notes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
