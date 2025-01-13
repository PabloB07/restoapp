<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tip extends Model
{
    protected $fillable = [
        'order_id',
        'percentage',
        'amount',
        'is_accepted'
    ];

    protected $casts = [
        'is_accepted' => 'boolean',
        'percentage' => 'decimal:2',
        'amount' => 'decimal:2'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
