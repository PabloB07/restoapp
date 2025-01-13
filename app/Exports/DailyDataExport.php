<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DailyDataExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $orders = Order::with(['items.product', 'table'])
            ->whereDate('created_at', now()->today())
            ->get();

        $data = [];

        foreach ($orders as $order) {
            $totalWithTax = $order->total * 1.19; // Total con IVA
            foreach ($order->items as $item) {
                $data[] = [
                    'mesa' => $order->table->title,
                    'numero_mesa' => $order->table->number,
                    'total_final_con_iva' => number_format($totalWithTax, 2, ',', '.'),
                    'nombre_producto' => $item->product->name,
                    'descripcion' => $item->product->description ?? 'Sin descripción',
                    'mesero' => $order->user->username,
                    'fecha_hora' => $order->created_at->format('d/m/Y H:i'),
                ];
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Mesa',
            'Número de Mesa',
            'Total Final con IVA',
            'Nombre del Producto',
            'Descripción',
            'Mesero',
            'Fecha/Hora',
        ];
    }
}
