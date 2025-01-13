<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\CompanySettings;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['table', 'orderItems.product', 'user', 'tip']);

        // Aplicar filtro por estado si existe
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()
                       ->paginate(10)
                       ->withQueryString(); // Mantiene los parámetros de la URL en la paginación

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['table', 'user', 'orderItems.product'])->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function create(Table $table)
    {
        if (!$table) {
            return redirect()->route(auth()->user()->role . '.tables.index')
                ->with('error', 'Mesa no encontrada.');
        }

        $products = Product::where('available', true)->get();
        return view('orders.create', compact('table', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $table = Table::find($request->table_id);
        if (!$table) {
            return redirect()->back()
                ->with('error', 'Mesa no encontrada.')
                ->withInput();
        }

        try {
            \DB::beginTransaction();

            $order = Order::create([
                'table_id' => $request->table_id,
                'user_id' => auth()->id(),
                'status' => 'pending',
                'total' => 0,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                    'notes' => $item['notes'] ?? null,
                ]);

                $total += $subtotal;
            }

            $order->update(['total' => $total]);

            // Crear propina sugerida
            $tipPercentage = 10.00; // 10% por defecto
            $tipAmount = $total * ($tipPercentage / 100);

            $order->tip()->create([
                'percentage' => $tipPercentage,
                'amount' => $tipAmount,
                'is_accepted' => false
            ]);

            $table->update(['status' => 'occupied']);

            \DB::commit();

            return redirect()->route(auth()->user()->role . '.orders.index')
                ->with('success', 'Orden creada exitosamente.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al crear la orden: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'table_id' => 'required|exists:tables,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

    try {
        \DB::beginTransaction();

        // Calcular el nuevo total
        $total = 0;

        // Eliminar los items existentes
        $order->orderItems()->delete();

        // Crear los nuevos items
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $subtotal = $product->price * $item['quantity'];

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $subtotal,
                'notes' => $item['notes'] ?? null,
            ]);

            $total += $subtotal;
        }

        // Actualizar el total de la orden
        $order->update([
            'total' => $total
        ]);

        \DB::commit();

        return redirect()->route(auth()->user()->role . '.orders.index')
            ->with('success', 'Orden actualizada exitosamente.');

    } catch (\Exception $e) {
        \DB::rollBack();
        return redirect()->back()
            ->with('error', 'Error al actualizar la orden: ' . $e->getMessage())
            ->withInput();
    }
}

    public function updateStatus(Order $order)
    {
        try {
            // Validar el estado actual y determinar el siguiente
            $newStatus = match ($order->status) {
                'pending' => 'in_progress',
                'in_progress' => 'completed',
                default => throw new \Exception('No se puede actualizar el estado actual.')
            };

            // Actualizar el estado
            $order->update([
                'status' => $request->status ?? $newStatus,
                'completed_at' => $newStatus === 'completed' ? now() : null
            ]);

            // Registrar el cambio en el log
            \Log::info("Orden #{$order->id} actualizada a estado: {$newStatus}");

            return redirect()->back()->with('success', 'Estado de la orden actualizado correctamente.');

        } catch (\Exception $e) {
            \Log::error("Error actualizando estado de orden #{$order->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar el estado de la orden: ' . $e->getMessage());
        }
    }

    public function complete(Order $order)
    {
        if (!$order || $order->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'La orden no existe o no está pendiente.');
        }

        try {
            \DB::beginTransaction();

            $order->update(['status' => 'completed']);

            if (!$order->table->orders()->where('status', 'pending')->exists()) {
                $order->table->update(['status' => 'available']);
            }

            \DB::commit();

            return redirect()->route(auth()->user()->role . '.orders.index')
                ->with('success', 'Orden completada exitosamente');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al completar la orden: ' . $e->getMessage());
        }
    }

    public function cancel(Order $order)
    {
        if (!$order || $order->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'La orden no existe o no está pendiente.');
        }

        try {
            \DB::beginTransaction();

            $order->update(['status' => 'cancelled']);

            if (!$order->table->orders()->where('status', 'pending')->exists()) {
                $order->table->update(['status' => 'available']);
            }

            \DB::commit();

            return redirect()->route(auth()->user()->role . '.orders.index')
                ->with('success', 'Orden cancelada exitosamente');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al cancelar la orden: ' . $e->getMessage());
        }
    }

    public function printVoucher(Order $order, ?string $area = null, ?string $role = 'admin')
    {
        try {
            // Validar el área si se proporciona
            if ($area && !in_array($area, ['kitchen', 'bar', 'grill', 'customer'])) {
                return redirect()->back()->with('error', 'Área no válida.');
            }

            $companySettings = CompanySettings::first();
            $data = [
                'order' => $order,
                'companySettings' => $companySettings,
                'role' => $role
            ];

            // Si es voucher de cliente, usar vista específica
            if ($area === 'customer') {
                return view('orders.vouchers.customer-copy', $data);
            }

            // Si no hay área específica, imprimir todos los vouchers
            if (!$area) {
                $data['hasItems'] = [
                    'kitchen' => $order->hasItemsInArea('kitchen'),
                    'bar' => $order->hasItemsInArea('bar'),
                    'grill' => $order->hasItemsInArea('grill')
                ];
                return view('orders.vouchers.all', $data);
            }

            // Verificar si hay items para el área específica
            if (!$order->hasItemsInArea($area)) {
                return redirect()->back()->with('error', "No hay items de {$area}.");
            }

            $data['area'] = $area;
            $data['items'] = $order->getAreaItems($area);

            return view('orders.vouchers.print', $data);

        } catch (\Exception $e) {
            \Log::error('Error printing voucher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al imprimir el voucher: ' . $e->getMessage());
        }
    }

    public function updateTip(Request $request, Order $order)
    {
        $request->validate([
            'tip_accepted' => 'required|boolean',
            'tip_percentage' => 'required_if:tip_accepted,true|numeric|min:0|max:100'
        ]);

        if ($request->tip_accepted) {
            $tipAmount = ($order->total * $request->tip_percentage) / 100;

            $order->tip()->updateOrCreate(
                ['order_id' => $order->id],
                [
                    'percentage' => $request->tip_percentage,
                    'amount' => $tipAmount,
                    'is_accepted' => true
                ]
            );
        } else {
            if ($order->tip) {
                $order->tip->update(['is_accepted' => false]);
            }
        }

        return back()->with('success', 'Propina actualizada correctamente');
    }

    // Agregar estos métodos al modelo Order si no existen
    public function getTotalWithTaxAttribute()
    {
        return $this->total * 1.19; // 19% IVA
    }

    public function getTotalWithTipAttribute()
    {
        if ($this->tip && $this->tip->is_accepted) {
            return $this->total + $this->tip->amount;
        }
        return $this->total;
    }

    public function getTotalWithTaxAndTipAttribute()
    {
        $totalWithTax = $this->total_with_tax;
        if ($this->tip && $this->tip->is_accepted) {
            return $totalWithTax + $this->tip->amount;
        }
        return $totalWithTax;
    }

    public function edit(Order $order)
    {
        try {
            // Cargar la orden con sus relaciones
            $order->load(['table', 'orderItems.product']);

            // Obtener todos los productos disponibles
            $products = Product::where('available', true)->get();

            // Preparar los items existentes para el formulario
            $orderItems = $order->orderItems->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'notes' => $item->notes,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal
                ];
            });

            return view('orders.edit', compact('order', 'products', 'orderItems'));

        } catch (\Exception $e) {
            \Log::error('Error editing order: ' . $e->getMessage());
            return redirect()->route('admin.orders.index')
                ->with('error', 'Error al editar la orden: ' . $e->getMessage());
        }
    }

    // Asegúrate de que el modelo Order tenga estos estados definidos
    protected $casts = [
        'status' => 'string',
        'completed_at' => 'datetime'
    ];

    // Agregar estos métodos al modelo Order si no existen
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'in_progress' => 'En Progreso',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            default => 'Desconocido'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
