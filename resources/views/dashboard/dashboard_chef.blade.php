@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header con logo -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Panel de Control - Chef</h2>
                <p class="text-gray-600">Resumen de Servicio de Chef</p>
            </div>
            <img src="{{ asset('images/vastago_letras.png') }}" alt="Restaurante Vastago" class="h-16">
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Órdenes Pendientes -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-xl p-6 shadow-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Órdenes Pendientes</p>
                        <p class="text-3xl font-bold mt-1">{{ \App\Models\Order::where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-2">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Órdenes en Preparación -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Mesas</p>
                        <p class="text-3xl font-bold mt-1">
                            {{ \App\Models\Table::where('status', 'occupied')->count() }} /
                            {{ \App\Models\Table::where('status', 'available')->count() }}
                        </p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-2">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 8l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Productos -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Total Productos</p>
                        <p class="text-3xl font-bold mt-1">{{ \App\Models\Product::count() }}</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-2">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Stock Bajo -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-xl p-6 shadow-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Stock Bajo</p>
                        <p class="text-3xl font-bold mt-1">{{ \App\Models\Inventory::whereRaw('quantity <= minimum_stock')->count() }}</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-2">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Órdenes -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Gestión de Órdenes</h3>
                        <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                            {{ \App\Models\Order::whereIn('status', ['pending', 'in_progress'])->count() }} activas
                        </span>
                    </div>
                    <p class="text-gray-600 mb-6">Control y preparación de órdenes</p>
                    <a href="{{ route(auth()->user()->role . '.orders.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver Órdenes
                    </a>
                </div>
            </div>

            <!-- Inventario -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Control de Inventario</h3>
                        <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">
                            {{ \App\Models\Inventory::whereRaw('quantity <= minimum_stock')->count() }} bajo stock
                        </span>
                    </div>
                    <p class="text-gray-600 mb-6">Gestión de stock y productos</p>
                    <a href="{{ route(auth()->user()->role . '.inventory.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Ver Inventario
                    </a>
                </div>
            </div>

            <!-- Proveedores -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Proveedores</h3>
                        <span class="px-3 py-1 text-sm rounded-full bg-purple-100 text-purple-800">
                            {{ \App\Models\Purchase::where('status', 'pending')->count() }} pendientes
                        </span>
                    </div>
                    <p class="text-gray-600 mb-6">Gestión de proveedores y compras</p>
                    <a href="{{ route(auth()->user()->role . '.purchases.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Ver Proveedores
                    </a>
                </div>
            </div>

            <!-- Productos -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Productos</h3>
                        <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                            {{ \App\Models\Product::count() }} total
                        </span>
                    </div>
                    <p class="text-gray-600 mb-6">Gestión de productos y menú</p>
                    <a href="{{ route(auth()->user()->role . '.products.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Ver Productos
                    </a>
                </div>
            </div>

            <!-- Mesas -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Estado de Mesas</h3>
                        <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">
                            {{ \App\Models\Table::where('status', 'occupied')->count() }} ocupadas
                        </span>
                    </div>
                    <p class="text-gray-600 mb-6">Monitoreo de mesas y pedidos</p>
                    <a href="{{ route(auth()->user()->role . '.tables.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Ver Mesas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Se eliminó el código de los gráficos ya que no son necesarios
});
</script>
@endpush
@endsection
