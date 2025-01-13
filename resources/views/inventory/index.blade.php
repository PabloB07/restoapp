@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Inventario</h2>
            <a href="{{ route(auth()->user()->role . '.inventory.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-md transition-colors duration-200">
                Nuevo Item
            </a>
        </div>

            <!-- Tabla de Inventario -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Producto</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Stock Actual</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Stock Mínimo</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Unidad</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Estado</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($inventories as $inventory)
                                    <tr class="hover:bg-gray-100 transition-colors">
                                        <td class="px-4 py-2 text-gray-800">{{ $inventory->product->name }}</td>
                                        <td class="px-4 py-2 text-gray-800">{{ $inventory->quantity }}</td>
                                        <td class="px-4 py-2 text-gray-800">{{ $inventory->minimum_stock }}</td>
                                        <td class="px-4 py-2 text-gray-800">{{ $inventory->unit }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $inventory->getStatusColorClass() }}">
                                                {{ $inventory->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route(auth()->user()->role . '.inventory.edit', $inventory) }}"
                                               class="text-blue-500 hover:text-blue-700 font-medium transition-colors">
                                                Editar
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
