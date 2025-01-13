@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Nueva Compra</h2>
            </div>

            <form action="{{ route(auth()->user()->role . '.purchases.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <!-- Información básica -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Proveedor</label>
                            <input type="text" name="supplier" value="{{ old('supplier') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                            @error('supplier')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-2">N° Factura</label>
                            <input type="text" name="invoice_number" value="{{ old('invoice_number') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                            @error('invoice_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Productos -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Productos</h3>
                            <button type="button" onclick="addItem()"
                                class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Agregar Producto
                            </button>
                        </div>

                        <div id="purchase-items" class="space-y-4">
                            <!-- Items se agregarán aquí -->
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-between items-center pt-6 border-t">
                        <div class="text-2xl font-bold text-gray-900">
                            Total: $<span id="total" class="text-blue-600">0</span>
                        </div>

                        <div class="flex space-x-3">
                            <a href="{{ route(auth()->user()->role . '.purchases.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition-colors duration-200">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Guardar Compra
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let itemCount = 1; // Start from 1 since we already have one item

    function addItem() {
        const itemHtml = `
            <div class="border-b pb-4 mb-4" id="item-${itemCount}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Producto</label>
                        <select name="items[${itemCount}][product_id]" onchange="updateTotal()"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Seleccionar producto</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                        <input type="number" name="items[${itemCount}][quantity]" value="1" min="1" step="1"
                            onchange="updateTotal()"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Precio Unitario</label>
                        <input type="number" name="items[${itemCount}][unit_price]" value="0" min="0" step="1"
                            onchange="updateTotal()"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
                <button type="button" onclick="removeItem(${itemCount})"
                    class="mt-2 text-red-600 hover:text-red-800">
                    Eliminar
                </button>
            </div>
        `;

        document.getElementById('purchase-items').insertAdjacentHTML('beforeend', itemHtml);
        itemCount++;
    }

    function removeItem(index) {
        document.getElementById(`item-${index}`).remove();
        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        const items = document.querySelectorAll('[id^="item-"]');

        items.forEach(item => {
            const quantity = parseInt(item.querySelector('input[name$="[quantity]"]').value) || 0;
            const unitPrice = parseInt(item.querySelector('input[name$="[unit_price]"]').value) || 0;
            total += quantity * unitPrice;
        });

        document.getElementById('total').textContent = total.toLocaleString('es-CL');
    }
</script>
@endpush
@endsection
