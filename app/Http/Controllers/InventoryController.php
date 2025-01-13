<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::with('product')
            ->orderBy('quantity', 'asc')
            ->get();
        return view('inventory.index', compact('inventories'));
    }

    public function create()
    {
        $products = Product::whereDoesntHave('inventory')->get();
        return view('inventory.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id|unique:inventories',
            'quantity' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'unit' => 'required|string|in:unidad,kg,caja,lt,gr'
        ]);

        $inventory = Inventory::create($validated);
        $inventory->updateStockStatus();

        return redirect()
            ->route(auth()->user()->role . '.inventory.index')
            ->with('success', 'Item de inventario creado exitosamente');
    }

    public function edit(Inventory $inventory)
    {
        $products = Product::all();
        return view('inventory.edit', compact('inventory', 'products'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'unit' => 'required|string|in:unidad,kg,caja,lt,gr'
        ]);

        $inventory->update($validated);
        $inventory->updateStockStatus();

        return redirect()
            ->route(auth()->user()->role . '.inventory.index')
            ->with('success', 'Inventario actualizado exitosamente');
    }

    public function adjustStock(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'adjustment' => 'required|numeric',
            'reason' => 'required|string|max:255',
        ]);

        $newQuantity = $inventory->quantity + $validated['adjustment'];

        if ($newQuantity < 0) {
            return back()->with('error', 'La cantidad no puede ser negativa');
        }

        $inventory->quantity = $newQuantity;
        $inventory->updateStockStatus();
        $inventory->save();

        return redirect()
            ->route(auth()->user()->role . '.inventory.index')
            ->with('success', 'Stock ajustado exitosamente');
    }
}
