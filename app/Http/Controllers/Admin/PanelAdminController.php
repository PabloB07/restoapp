<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySettings;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Ingredient;
use App\Models\CashBox;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class PanelAdminController extends Controller
{
    public function index(Request $request)
    {
        $timeRange = $request->get('timeRange', 'day');

        // Determinar fechas según el rango
        $startDate = now();
        $previousStartDate = now(); // Para el período anterior

        switch($timeRange) {
            case 'week':
                $startDate = $startDate->startOfWeek();
                $previousStartDate = $previousStartDate->subWeek()->startOfWeek();
                break;
            case 'month':
                $startDate = $startDate->startOfMonth();
                $previousStartDate = $previousStartDate->subMonth()->startOfMonth();
                break;
            default: // day
                $startDate = $startDate->startOfDay();
                $previousStartDate = $previousStartDate->subDay()->startOfDay();
        }

        // Obtener datos de ventas del período actual
        $salesData = Order::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Calcular ingresos del período anterior
        $previousPeriodIncome = Order::where('created_at', '>=', $previousStartDate)
            ->where('created_at', '<', $startDate)
            ->sum('total');

        // Calcular totales
        $totalIncome = $salesData->sum('total');
        $totalOrders = Order::where('created_at', '>=', $startDate)->count();
        $averageDailyIncome = $totalIncome / max(1, $salesData->count());
        $averageOrderValue = $totalOrders > 0 ? $totalIncome / $totalOrders : 0;

        // Obtener productos más vendidos
        $topProducts = OrderItem::with('product')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('product_id, SUM(quantity) as quantity')
            ->groupBy('product_id')
            ->orderByDesc('quantity')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity
                ];
            });

        // Resumen diario
        $dailySummary = Order::where('created_at', '>=', $startDate)
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total_orders,
                SUM(total) as total_sales,
                AVG(total) as average_order
            ')
            ->groupBy('date')
            ->orderByDesc('date')
            ->get();

        // Obtener datos de mesas
        $totalTables = Table::count();
        $usedTables = Table::where('status', 'occupied')->count();
        $tableOccupancyRate = $totalTables > 0 ? ($usedTables / $totalTables) * 100 : 0;

        // Calcular órdenes pendientes
        $pendingOrders = Order::where('status', 'pending')->count();

        $companySettings = CompanySettings::updateCompanySettings();

        return view('dashboard.index', compact(
            'timeRange',
            'totalIncome',
            'totalOrders',
            'averageDailyIncome',
            'averageOrderValue',
            'salesData',
            'topProducts',
            'dailySummary',
            'previousPeriodIncome',
            'totalTables',
            'usedTables',
            'tableOccupancyRate',
            'companySettings',
            'pendingOrders'
        ));
    }

    public function showCompanySettings()
    {
        $companySettings = CompanySettings::updateCompanySettings();
        return view('admin.company-settings', compact('companySettings'));
    }

    public function updateCompanySettings(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'business_line' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'rut' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $settings = CompanySettings::first() ?? new CompanySettings();
        $settings->fill($validated);
        $settings->save();

        return redirect()->back()->with('success', 'Datos de la empresa actualizados correctamente');
    }
}
