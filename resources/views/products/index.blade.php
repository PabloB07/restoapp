@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-semibold">Productos</h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route(auth()->user()->role . '.products.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Nuevo Producto
                </a>
            </div>
        </div>

        <!-- Grid de Productos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden p-3 sm:p-4">
                <!-- Imagen del producto -->
                <div class="w-full h-48 bg-gray-200">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Información del producto -->
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ $product->name }}</h3>

                    <div class="mt-2 flex items-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $product->category === 'comida' ? 'bg-yellow-100 text-yellow-800' :
                               ($product->category === 'bebestible' ? 'bg-blue-100 text-blue-800' :
                                'bg-pink-100 text-pink-800') }}">
                            {{ ucfirst($product->category) }}
                        </span>
                    </div>

                    <div class="mt-3 flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">
                            ${{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="text-sm {{ $product->inventory ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->inventory ? $product->inventory->quantity . ' unid.' : 'Sin stock' }}
                        </span>
                    </div>

                    <!-- Estado y Acciones -->
                    <div class="mt-4 flex justify-between items-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $product->available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->available ? 'Disponible' : 'No disponible' }}
                        </span>

                        <div class="flex space-x-2">
                            <a href="{{ route(auth()->user()->role . '.products.edit', $product) }}"
                                class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route(auth()->user()->role . '.products.destroy', $product) }}"
                                method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('¿Estás seguro de eliminar este producto?')"
                                    class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
