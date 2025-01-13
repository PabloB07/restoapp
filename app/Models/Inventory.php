<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'minimum_stock',
        'unit',
        'status'
    ];

    protected $attributes = [
        'status' => 'Disponible'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function updateStockStatus()
    {
        if ($this->quantity <= 0) {
            $this->status = 'Sin stock';
        } elseif ($this->quantity <= $this->minimum_stock) {
            $this->status = 'Stock bajo';
        } else {
            $this->status = 'Disponible';
        }
        $this->save();
    }

    public function getStatusColorClass()
    {
        return match($this->status) {
            'Sin stock' => 'bg-red-100 text-red-800',
            'Stock bajo' => 'bg-yellow-100 text-yellow-800',
            'Disponible' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
