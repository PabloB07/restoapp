<?php

namespace App\Http\Controllers;

use App\Models\CashBox;
use App\Models\Order;
use Illuminate\Http\Request;

class CashBoxController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $cashBox = CashBox::where('date', $date)->first();

        return view('cashbox.index', compact('cashBox', 'date'));
    }

    public function open(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        if (CashBox::where('date', $date)->exists()) {
            return redirect()->back()->with('error', 'La caja ya está abierta para este día.');
        }

        CashBox::create(['date' => $date, 'total_income' => 0]);

        return redirect()->route('cashbox.index', ['date' => $date])->with('success', 'Caja abierta exitosamente.');
    }

    public function close(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $cashBox = CashBox::where('date', $date)->first();

        if (!$cashBox) {
            return redirect()->back()->with('error', 'No hay caja abierta para este día.');
        }

        // Sumar los ingresos de las órdenes completadas
        $totalIncome = Order::whereDate('created_at', $date)
            ->where('status', 'completed')
            ->sum('total');

        // Actualizar el total de ingresos en la caja
        $cashBox->total_income += $totalIncome;
        $cashBox->save(); // Guarda los cambios

        return redirect()->route('cashbox.index', ['date' => $date])->with('success', 'Caja cerrada exitosamente. Ingresos totales: $' . number_format($cashBox->total_income, 2));
    }
}
