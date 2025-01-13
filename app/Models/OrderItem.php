<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Relación con Order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Relación con Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Calcular subtotal
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->subtotal = $item->quantity * $item->price;
        });
    }
    // Método para calcular el subtotal
    public function calculateSubtotal()
    {
        $this->subtotal = $this->quantity * $this->price;
        return $this->subtotal;
    }
}
