<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Table extends Model
{

    const STATUS_AVAILABLE = 'available';
    const STATUS_OCCUPIED = 'occupied';


    protected $fillable = ['number', 'title', 'status', 'capacity'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function current_order(): HasOne
    {
        return $this->hasOne(Order::class)
            ->where('status', '!=', Order::STATUS_COMPLETED)
            ->where('status', '!=', Order::STATUS_CANCELLED)
            ->latestOfMany();
    }
    public function activeOrders()
    {
        return $this->orders()
        ->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_IN_PROGRESS]);
    }

    public function isOccupied(): bool
    {
        return $this->status === self::STATUS_OCCUPIED;
    }

    public function hasActiveOrders()
    {
        return $this->orders()->where('status', 'pending')->exists();
    }


}
