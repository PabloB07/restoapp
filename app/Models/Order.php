<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'table_id',
        'user_id',
        'status',
        'total',
        'tip'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'tip' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $with = ['table', 'user']; // Eager loading por defecto

    // Definir los estados posibles
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Relaciones
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class)->withDefault([
            'number' => 'Mesa no asignada',
        ]);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Atributos computados
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_IN_PROGRESS => 'badge-info',
            self::STATUS_COMPLETED => 'badge-success',
            self::STATUS_CANCELLED => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_IN_PROGRESS => 'En Preparación',
            self::STATUS_COMPLETED => 'Completada',
            self::STATUS_CANCELLED => 'Cancelada',
            default => 'Desconocido'
        };
    }

    // Scopes para filtrar órdenes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    // Método para calcular el total
    public function calculateTotal()
    {
        $this->total = $this->items->sum('subtotal');
        $this->save();
    }

    // Boot method para manejar eventos del modelo
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->status) {
                $order->status = self::STATUS_PENDING;
            }
        });
    }
    // Métodos para obtener items por área
    public function getKitchenItems()
    {
        return $this->orderItems()->whereHas('product', function($query) {
            $query->where('area', 'kitchen');
        })->get();
    }

    public function getBarItems()
    {
        return $this->orderItems()->whereHas('product', function($query) {
            $query->where('area', 'bar');
        })->get();
    }

    public function getGrillItems()
    {
        return $this->orderItems()->whereHas('product', function($query) {
            $query->where('area', 'grill');
        })->get();
    }

    public function hasItemsInArea($area)
    {
        return $this->items->contains(function ($item) use ($area) {
            return $item->product->area === $area;
        });
    }

    public function tip()
    {
        return $this->hasOne(Tip::class);
    }

    public function getTotalWithTaxAttribute()
    {
        return $this->total * 1.19; // Total + 19% IVA
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
}
