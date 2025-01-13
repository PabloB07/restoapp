@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">Editar Orden #{{ $order->id }}</h2>
                    <p class="text-gray-600">Mesa {{ $order->table->number }} - {{ $order->table->title }}</p>
                </div>
                <span class="px-3 py-1 text-sm rounded-full {{ $order->status_color }}">
                    {{ $order->status_text }}
                </span>
            </div>

            <form action="{{ route(auth()->user()->role . '.orders.update', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="table_id" value="{{ $order->table->id }}">

                <div class="space-y-6">
                    <div id="order-items">
                        @foreach($order->orderItems as $item)
                        <div class="bg-gray-50 rounded-lg p-6 mb-4" id="item-{{ $loop->index }}">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Producto</label>
                                    <select name="items[{{ $loop->index }}][product_id]"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                            onchange="updatePrice({{ $loop->index }})">
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}"
                                                    data-price="{{ $product->price }}"
                                                    {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                                {{ $product->name }} - ${{ number_format($product->price, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                                    <input type="number"
                                           name="items[{{ $loop->index }}][quantity]"
                                           value="{{ $item->quantity }}"
                                           min="1"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                           onchange="updatePrice({{ $loop->index }})">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                                    <div class="mt-2 text-lg font-semibold">
                                        $<span id="subtotal-{{ $loop->index }}">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Notas</label>
                                <textarea name="items[{{ $loop->index }}][notes]"
                                          rows="2"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                          placeholder="Instrucciones especiales...">{{ $item->notes }}</textarea>
                            </div>
                            <button type="button"
                                    onclick="removeItem({{ $loop->index }})"
                                    class="mt-4 text-red-600 hover:text-red-800 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Eliminar
                            </button>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <button type="button"
                                onclick="addItem()"
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

                    <div class="mt-4 border-t pt-4">
                        <h3 class="text-lg font-medium mb-2">Propina</h3>
                        <form action="{{ route('admin.orders.update-tip', $order) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="flex items-center mb-4">
                                <input type="checkbox" name="tip_accepted" id="tip_accepted"
                                       class="rounded border-gray-300"
                                       {{ $order->tip && $order->tip->is_accepted ? 'checked' : '' }}
                                       onchange="document.getElementById('tip_percentage').disabled = !this.checked">
                                <label for="tip_accepted" class="ml-2">Aceptar propina</label>
                            </div>

                            <div class="flex items-center">
                                <select name="tip_percentage" id="tip_percentage"
                                        class="rounded-md border-gray-300"
                                        {{ !($order->tip && $order->tip->is_accepted) ? 'disabled' : '' }}>
                                    <option value="10" {{ $order->tip && $order->tip->percentage == 10 ? 'selected' : '' }}>10%</option>
                                    <option value="15" {{ $order->tip && $order->tip->percentage == 15 ? 'selected' : '' }}>15%</option>
                                    <option value="20" {{ $order->tip && $order->tip->percentage == 20 ? 'selected' : '' }}>20%</option>
                                </select>
                                <button type="submit"
                                        class="ml-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                    Actualizar Propina
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route(auth()->user()->role . '.orders.index') }}"
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
                            Actualizar Orden
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
let itemCount = {{ $order->orderItems->count() }};

function formatNumber(number) {
    return new Intl.NumberFormat('es-CL', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(number);
}

function parseFormattedNumber(str) {
    return parseInt(str.replace(/\D/g, '')) || 0;
}

function updatePrice(index) {
    const select = document.querySelector(`[name="items[${index}][product_id]"]`);
    const quantityInput = document.querySelector(`[name="items[${index}][quantity]"]`);
    const quantity = parseInt(quantityInput.value) || 0;

    if (!select || select.selectedIndex === -1) return;

    const price = parseFloat(select.options[select.selectedIndex].dataset.price) || 0;
    const subtotal = price * quantity;

    document.getElementById(`subtotal-${index}`).textContent = formatNumber(subtotal);
    calculateTotal();
}

function calculateTotal() {
    const subtotals = document.querySelectorAll('[id^="subtotal-"]');
    const subtotal = Array.from(subtotals)
        .reduce((sum, el) => sum + parseFormattedNumber(el.textContent), 0);

    const total = subtotal * 1.19; // Incluye IVA
    document.getElementById('total').textContent = formatNumber(total);
}

function addItem() {
    const itemHtml = `
        <div class="bg-gray-50 rounded-lg p-6 mb-4" id="item-${itemCount}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Producto</label>
                    <select name="items[${itemCount}][product_id]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                            onchange="updatePrice(${itemCount})">
                        <option value="">Seleccionar producto</option>
                        ${products.map(p => `
                            <option value="${p.id}" data-price="${p.price}">
                                ${p.name} - $${formatNumber(p.price)}
                            </option>
                        `).join('')}
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                    <input type="number"
                           name="items[${itemCount}][quantity]"
                           value="1"
                           min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                           onchange="updatePrice(${itemCount})">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                    <div class="mt-2 text-lg font-semibold">$<span id="subtotal-${itemCount}">0</span></div>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Notas</label>
                <textarea name="items[${itemCount}][notes]"
                          rows="2"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                          placeholder="Instrucciones especiales..."></textarea>
            </div>
            <button type="button"
                    onclick="removeItem(${itemCount})"
                    class="mt-4 text-red-600 hover:text-red-800 inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Eliminar
            </button>
        </div>
    `;

    document.getElementById('order-items').insertAdjacentHTML('beforeend', itemHtml);
    updatePrice(itemCount);
    itemCount++;
}

function removeItem(index) {
    document.getElementById(`item-${index}`).remove();
    calculateTotal();
}

// Inicializar totales al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('[id^="item-"]');
    items.forEach((item, index) => {
        updatePrice(index);
    });
    calculateTotal();
});
</script>
@endpush
@endsection
