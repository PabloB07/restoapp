@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-6">Reporte de Datos Diarios</h2>

            <p class="mb-4">Puedes descargar el reporte diario en formato Excel.</p>

            <div class="flex justify-end">
                <a href="{{ route('data.report.export') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fas fa-download mr-2"></i> Descargar Reporte
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
