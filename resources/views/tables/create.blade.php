@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-6">Crear Mesa</h2>

            @if ($errors->any())
                <div class="bg-red-500 text-white p-4 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route(auth()->user()->role . '.tables.store') }}" method="POST">
                @csrf
                <div id="tables-container" class="space-y-6">
                    <div class="table-entry">
                        <label class="block text-sm font-medium text-gray-700">Número</label>
                        <input type="number" name="tables[0][number]" value="{{ old('tables.0.number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>

                        <label class="block text-sm font-medium text-gray-700">Capacidad</label>
                        <input type="number" name="tables[0][capacity]" value="{{ old('tables.0.capacity', 4) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>

                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="tables[0][status]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="available" {{ old('tables.0.status') == 'available' ? 'selected' : '' }}>Disponible</option>
                            <option value="occupied" {{ old('tables.0.status') == 'occupied' ? 'selected' : '' }}>Ocupada</option>
                        </select>
                    </div>
                </div>

                <button type="button" id="add-table" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                  +  Agregar Otra Mesa
                </button>

                <div class="flex justify-end space-x-2 mt-4">
                    <a href="{{ route(auth()->user()->role . '.tables.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancelar</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear Mesas</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let tableCount = 0;
    const container = document.getElementById('tables-container');
    const addButton = document.getElementById('add-table');

    addButton.addEventListener('click', function() {
        tableCount++;
        const newTable = document.createElement('div');
        newTable.className = 'table-entry mt-6 p-4 border rounded';
        newTable.innerHTML = `
            <div class="flex justify-end">
                <button type="button" class="text-red-500 hover:text-red-700" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <label class="block text-sm font-medium text-gray-700">Número</label>
            <input type="number" name="tables[${tableCount}][number]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>

            <label class="block text-sm font-medium text-gray-700 mt-4">Capacidad</label>
            <input type="number" name="tables[${tableCount}][capacity]" value="4" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>

            <label class="block text-sm font-medium text-gray-700 mt-4">Estado</label>
            <select name="tables[${tableCount}][status]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <option value="available">Disponible</option>
                <option value="occupied">Ocupada</option>
            </select>
        `;
        container.appendChild(newTable);
    });
});
</script>
@endsection
