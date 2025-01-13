@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold">Panel de Control y Estadísticas - Administrador</h2>
            <img src="{{ asset('images/vastago_black.png') }}" alt="Restaurante Vastago" class="h-16">
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <!-- Pending Orders -->
            <div class="bg-blue-500 text-white rounded-lg p-6 relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xl font-semibold">{{ \App\Models\Order::where('status', 'pending')->count() }}</p>
                        <p class="text-sm opacity-90">Órdenes Pendientes</p>
                    </div>
                    <div class="opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Tables -->
            <div class="bg-green-500 text-white rounded-lg p-6 relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xl font-semibold">{{ \App\Models\Table::count() }}</p>
                        <p class="text-sm opacity-90">Total Mesas</p>
                    </div>
                    <div class="opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="bg-yellow-500 text-white rounded-lg p-6 relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xl font-semibold">{{ \App\Models\Product::count() }}</p>
                        <p class="text-sm opacity-90">Total Productos</p>
                    </div>
                    <div class="opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="bg-red-500 text-white rounded-lg p-6 relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xl font-semibold">{{ \App\Models\Inventory::whereRaw('quantity <= minimum_stock')->count() }}</p>
                        <p class="text-sm opacity-90">Productos Bajo Stock</p>
                    </div>
                    <div class="opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Mesas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Mesas</h3>
                        <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">
                            {{ \App\Models\Table::count() }} total
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4">Gestión de mesas y órdenes</p>
                    <a href="{{ route(auth()->user()->role . '.tables.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded">
                        Ver Mesas
                    </a>
                </div>
            </div>

            <!-- Órdenes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Órdenes</h3>
                        <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                            {{ \App\Models\Order::where('status', 'pending')->count() }} pendientes
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4">Control de órdenes y pedidos</p>
                    <a href="{{ route(auth()->user()->role . '.orders.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-700 text-white font-bold rounded">
                        Ver Órdenes
                    </a>
                </div>
            </div>

            <!-- Productos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Productos</h3>
                        <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                            {{ \App\Models\Product::count() }} total
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4">Gestión de productos y precios</p>
                    <a href="{{ route(auth()->user()->role . '.products.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-700 text-white font-bold rounded">
                        Ver Productos
                    </a>
                </div>
            </div>

            <!-- Inventario -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Inventario</h3>
                        <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">
                            {{ \App\Models\Inventory::whereRaw('quantity <= minimum_stock')->count() }} bajo stock
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4">Control de stock e inventario</p>
                    <a href="{{ route(auth()->user()->role . '.inventory.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white font-bold rounded">
                        Ver Inventario
                    </a>
                </div>
            </div>

            <!-- Proveedores -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Proveedores y Facturas</h3>
                        <span class="px-3 py-1 text-sm rounded-full bg-purple-100 text-purple-800">
                            {{ \App\Models\Purchase::where('status', 'pending')->count() }} pendientes
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4">Registro de Proveedores y Facturas</p>
                    <a href="{{ route(auth()->user()->role . '.purchases.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-700 text-white font-bold rounded">
                        Ver Proveedores
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
