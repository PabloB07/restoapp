@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-6">Caja</h2>

            <form action="{{ route('cashbox.index') }}" method="GET" class="mb-4">
                <label for="date" class="block mb-2">Selecciona la fecha:</label>
                <input type="date" name="date" id="date" value="{{ $date }}" class="border rounded p-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Seleccionar</button>
            </form>

            <p class="mb-4">Hoy es {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>

            @if ($cashBox)
                <p class="text-green-600">Caja abierta. Ingresos totales: ${{ number_format($cashBox->total_income, 2) }}</p>
                <form action="{{ route('cashbox.close', ['date' => $date]) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Cerrar Caja</button>
                </form>
            @else
                <form action="{{ route('cashbox.open', ['date' => $date]) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Abrir Caja</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
