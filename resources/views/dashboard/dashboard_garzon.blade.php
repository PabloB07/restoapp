@extends('layouts.app')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header con logo -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Panel de Control - Garzón</h2>
                <p class="text-gray-600">Resumen de Servicio de Garzón</p>
            </div>
            <img src="{{ asset('images/vastago_letras.png') }}" alt="Restaurante Vastago" class="h-16">
        </div>
      
      <div class="flex items-center space-x-2 text-gray-500 mb-5">
        <!-- Icono de inicio -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
          <path d="M10.707 1.293a1 1 0 00-1.414 0l-8 8A1 1 0 002 10h1v7a1 1 0 001 1h4a1 1 0 001-1v-4h2v4a1 1 0 001 1h4a1 1 0 001-1v-7h1a1 1 0 00.707-1.707l-8-8z" />
        </svg>
        <!-- Breadcrumbs -->
        <div class="flex items-center space-x-2">
          <a href="/" class="text-gray-500 hover:text-gray-700 text-sm">Inicio</a>
        </div>
      </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Mesas Activas -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Mesas Ocupadas</p>
                        <p class="text-3xl font-bold mt-1">{{ \App\Models\Table::where('status', 'occupied')->count() }}</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-2">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

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

            <!-- Productos Activos -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm opacity-90">Productos Disponibles</p>
                        <p class="text-3xl font-bold mt-1">{{ \App\Models\Product::where('status', 'active')->count() }}</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-2">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                    <a href="{{ route('garzon.tables.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Ver Mesas
                    </a>
                </div>
            </div>

            <!-- Órdenes -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Gestión de Órdenes</h3>
                        <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                            {{ \App\Models\Order::whereIn('status', ['pending', 'ready'])->count() }} activas
                        </span>
                    </div>
                    <p class="text-gray-600 mb-6">Control y seguimiento de órdenes</p>
                    <a href="{{ route('garzon.orders.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Ver Órdenes
                    </a>
                </div>
            </div>

            <!-- Productos -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Productos</h3>
                        <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                            {{ \App\Models\Product::where('status', 'active')->count() }} disponibles
                        </span>
                    </div>
                    <p class="text-gray-600 mb-6">Consulta de productos y stock</p>
                    <a href="{{ route('garzon.products.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Ver Productos
                    </a>
                </div>
            </div>
			@role('admin')
            <!-- Propinas -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Control de Propinas</h3>
                        <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                            {{ \App\Models\Order::whereHas('tip', function($query) {
                                $query->where('is_accepted', true);
                            })->count() }} aceptadas
                        </span>
                    </div>
                    <p class="text-gray-600 mb-6">Gestión de propinas y órdenes</p>
                    <a href="{{ route(auth()->user()->role . '.orders.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
            @endrole
        </div>
    </div>
</div>
@endsection
