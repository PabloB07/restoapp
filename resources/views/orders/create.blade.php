@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">Nueva Orden</h2>
                    <p class="text-gray-600">Mesa {{ $table->number }} - {{ $table->title }}</p>
                </div>
                <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                    En Proceso
                </span>
            </div>

            <form action="{{ route(auth()->user()->role . '.orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="table_id" value="{{ $table->id }}">

                <div class="space-y-6">
                    <div id="order-items" class="space-y-6">
                        <!-- Los items se agregarán aquí dinámicamente -->
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <button type="button" onclick="addItem()"
                            class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar Producto
                        </button>
                        <div class="text-xl font-bold text-gray-800">
                            Total: $<span id="total" class="text-blue-600">0</span>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route(auth()->user()->role . '.tables.index', ['table_id' => $table->id]) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Crear Orden
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const products = @json($products);
    let itemCount = 0;

    function addItem() {
        const itemHtml = `
            <div class="bg-gray-50 rounded-lg p-6" id="item-${itemCount}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 md:gap-6">
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Producto</label>
                        <select name="items[${itemCount}][product_id]"
                                onchange="updatePrice(${itemCount})"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <option value="">Seleccionar producto</option>
                            ${products.map(p => `
                                <option value="${p.id}" data-price="${p.price}">
                                    ${p.name} - $${p.price.toLocaleString('es-CL')}
                                </option>
                            `).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad</label>
                        <input type="number" name="items[${itemCount}][quantity]"
                               value="1" min="1"
                               onchange="updatePrice(${itemCount})"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subtotal</label>
                        <div class="text-lg font-semibold text-gray-800">
                            $<span id="subtotal-${itemCount}">0</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notas</label>
                    <textarea name="items[${itemCount}][notes]" rows="2"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        placeholder="Instrucciones especiales..."></textarea>
                </div>
                <button type="button" onclick="removeItem(${itemCount})"
                    class="mt-4 inline-flex items-center text-sm text-red-600 hover:text-red-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Eliminar
                </button>
            </div>
        `;

        document.getElementById('order-items').insertAdjacentHTML('beforeend', itemHtml);
        itemCount++;
    }

    function removeItem(index) {
        document.getElementById(`item-${index}`).remove();
        calculateTotal();
    }

    function updatePrice(index) {
        const select = document.querySelector(`[name="items[${index}][product_id]"]`);
        const quantity = document.querySelector(`[name="items[${index}][quantity]"]`).value;
        const price = select.options[select.selectedIndex].dataset.price;
        const subtotal = price * quantity;

        document.getElementById(`subtotal-${index}`).textContent = subtotal.toLocaleString('es-CL');
        calculateTotal();
    }

    function calculateTotal() {
        const subtotals = document.querySelectorAll('[id^="subtotal-"]');
        const subtotal = Array.from(subtotals)
            .map(el => parseInt(el.textContent.replace(/\D/g, '')) || 0)
            .reduce((sum, current) => sum + current, 0);

        const iva = Math.round(subtotal * 0.19);
        const total = subtotal + iva;

        document.getElementById('total').textContent = total.toLocaleString('es-CL');
    }

    // Agregar primer item al cargar
    addItem();
</script>
@endpush
@endsection
