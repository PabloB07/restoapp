<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Order;
use App\Models\CompanySettings;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::with('current_order')->paginate(10);
        return view('tables.index', compact('tables'));
    }

    public function show(Table $table)
    {
        $table->load(['current_order.items.product', 'orders' => function($query) {
            $query->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_IN_PROGRESS])
                  ->with('items.product');
        }]);

        return view('tables.show', compact('table'));
    }

    public function create()
    {
        return view('tables.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tables.*.number' => 'required|integer|unique:tables,number',
            'tables.*.capacity' => 'required|integer|min:1',
            'tables.*.status' => 'required|in:available,occupied',
        ]);

        foreach ($validatedData['tables'] as $tableData) {
            $tableData['title'] = 'Mesa ' . $tableData['number'];
            Table::create($tableData);
        }

        return redirect()->route(auth()->user()->role . '.tables.index')
            ->with('success', 'Mesas creadas exitosamente');
    }

    public function edit(Table $table)
    {
        return view('tables.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $validated = $request->validate([
            'number' => 'required|integer|unique:tables,number,' . $table->id,
            'title' => 'required|string|unique:tables,title,' . $table->id,
            'capacity' => 'required|integer',
            'status' => 'required|in:available,occupied',
        ]);

        $table->update($validated);

        return redirect()->route(auth()->user()->role . '.tables.index')
            ->with('success', 'Mesa actualizada exitosamente.');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->route(auth()->user()->role . '.tables.index')
            ->with('success', 'Mesa eliminada exitosamente.');
    }
    public function occupy(Table $table)
    {
        if ($table->isOccupied()) {
            return redirect()->back()
                ->with('error', 'La mesa ya está ocupada');
        }

        $table->update(['status' => Table::STATUS_OCCUPIED]);
        return redirect()->route(auth()->user()->role . '.orders.create', ['table' => $table]);
    }
    public function printVouchers(Table $table)
    {
        $currentOrder = $table->current_order;

        if (!$currentOrder) {
            return redirect()->back()
                ->with('error', 'No hay orden activa para esta mesa.');
        }

        $areas = ['kitchen', 'bar', 'grill'];
        $hasItems = [];

        foreach ($areas as $area) {
            $hasItems[$area] = $currentOrder->orderItems()
                ->whereHas('product', function($query) use ($area) {
                    $query->where('area', $area);
                })->exists();
        }

        return view('tables.vouchers.print-all', [
            'table' => $table,
            'order' => $currentOrder,
            'hasItems' => $hasItems
        ]);
    }

    public function printAreaVoucher(Table $table, $area)
    {
        $currentOrder = $table->current_order;

        if (!$currentOrder) {
            return redirect()->back()
                ->with('error', 'No hay orden activa para esta mesa.');
        }

        $hasItems = $currentOrder->orderItems()
            ->whereHas('product', function($query) use ($area) {
                $query->where('area', $area);
            })->exists();

        if (!$hasItems) {
            return redirect()->back()
                ->with('error', "No hay items de {$area} en esta orden.");
        }

        return view("tables.vouchers.{$area}", [
            'table' => $table,
            'order' => $currentOrder
        ]);
    }
}
