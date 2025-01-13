@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-white shadow-md rounded-lg">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Editar Compra</h1>
    <form action="{{ route(auth()->user()->role . '.purchases.update', $purchase->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label for="supplier" class="block text-gray-700 font-semibold mb-2">Proveedor</label>
            <input type="text" name="supplier" id="supplier" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500" value="{{ old('supplier', $purchase->supplier) }}" required>
        </div>

        <div class="mb-6">
            <label for="invoice_number" class="block text-gray-700 font-semibold mb-2">Número de Factura</label>
            <input type="text" name="invoice_number" id="invoice_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500" value="{{ old('invoice_number', $purchase->invoice_number) }}" required>
        </div>

        <div class="mb-6">
            <label for="date" class="block text-gray-700 font-semibold mb-2">Fecha</label>
            <input type="date" name="date" id="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500" value="{{ old('date', $purchase->date->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-6">
            <label for="status" class="block text-gray-700 font-semibold mb-2">Estado</label>
            <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500" required>
                <option value="pending" {{ old('status', $purchase->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="completed" {{ old('status', $purchase->status) == 'completed' ? 'selected' : '' }}>Completado</option>
                <option value="cancelled" {{ old('status', $purchase->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>

        <div class="flex justify-center">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
