@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Órdenes</h2>
                    <div class="flex space-x-4">
                        <select id="status-filter" class="rounded-md border-gray-300 shadow-sm">
                            <option value="">Todos los estados</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completada</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->table->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'in_progress') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'completed') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">${{ number_format($order->total * 1.19, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('d/m/Y g:i A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="{{ route(auth()->user()->role . '.orders.show', $order) }}"
                                           class="text-blue-600 hover:text-blue-900">Ver</a>

                                        @if($order->status == 'pending')
                                        <a href="{{ route(auth()->user()->role . '.orders.edit', $order) }}"
                                           class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                        @endif

                                        <div class="flex space-x-2">
                                            <a href="{{ route(auth()->user()->role . '.orders.vouchers.print', ['order' => $order, 'area' => 'kitchen']) }}"
                                               class="text-green-600 hover:text-green-900"
                                               target="_blank">Cocina</a>
                                            <a href="{{ route(auth()->user()->role . '.orders.vouchers.print', ['order' => $order, 'area' => 'bar']) }}"
                                               class="text-purple-600 hover:text-purple-900"
                                               target="_blank">Bar</a>
                                            <a href="{{ route(auth()->user()->role . '.orders.vouchers.print', ['order' => $order, 'area' => 'grill']) }}"
                                               class="text-orange-600 hover:text-orange-900"
                                               target="_blank">Parrilla</a>
                                            <a href="{{ route(auth()->user()->role . '.orders.vouchers.print', ['order' => $order, 'area' => 'customer']) }}"
                                               class="text-gray-600 hover:text-gray-900"
                                               target="_blank">Cliente</a>
                                        </div>

                                        @if($order->status == 'pending' || $order->status == 'in_progress')
                                        <form action="{{ route(auth()->user()->role . '.orders.update_status', $order) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="text-green-600 hover:text-green-900"
                                                    onclick="return confirm('¿Estás seguro de completar esta orden?')">
                                                Completar
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No hay órdenes para mostrar
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('status-filter').addEventListener('change', function() {
    const status = this.value;
    const currentUrl = new URL(window.location.href);

    if (status) {
        currentUrl.searchParams.set('status', status);
    } else {
        currentUrl.searchParams.delete('status');
    }

    window.location.href = currentUrl.toString();
});
</script>
@endpush
@endsection
