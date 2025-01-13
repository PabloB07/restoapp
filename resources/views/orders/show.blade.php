@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Orden #{{ $order->id }}</h2>
                <div class="flex space-x-4">
                    <span class="px-3 py-1 rounded-full text-sm
                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $order->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
            </div>

            <!-- Detalles de la orden -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Detalles de la Mesa</h3>
                    <p class="text-gray-600">Mesa: {{ $order->table->title }}</p>
                    <p class="text-gray-600">Mesero: {{ $order->user->username }}</p>
                    <p class="text-gray-600">Fecha: {{ $order->created_at->format('d/m/Y g:i A') }}</p>
                </div>
            </div>

            <!-- Items de la orden -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Items de la Orden</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4">{{ $item->quantity }}</td>
                                <td class="px-6 py-4">{{ $item->product->name }}</td>
                                <td class="px-6 py-4">${{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">${{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $item->notes }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Totales y Propina -->
            <div class="mt-6 border-t pt-6">
                <div class="flex justify-end">
                    <div class="w-full md:w-1/3 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">${{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">IVA (19%):</span>
                            <span class="font-medium">${{ number_format($order->total * 0.19, 0, ',', '.') }}</span>
                        </div>

                        @if($order->tip && $order->tip->is_accepted)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Propina ({{ $order->tip->percentage }}%):</span>
                                <span class="font-medium">${{ number_format($order->tip->amount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="border-t border-gray-200 pt-2">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total Final:</span>
                                <span>${{ number_format($order->total_with_tax_and_tip, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="mt-6 flex justify-end space-x-4">
                @if($order->status === 'pending')
                    <a href="{{ route(auth()->user()->role . '.orders.edit', $order) }}"
                       class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Editar Orden
                    </a>
                    <a href="{{ route(auth()->user()->role . '.orders.vouchers.print', ['order' => $order]) }}"
                       target="_blank"
                       class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        Imprimir Voucher
                    </a>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
