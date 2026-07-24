<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image',
        'type',
        'product_id',
        'discount_percentage',
        'discount_price',
        'start_date',
        'end_date',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('sort_order');
    }

    public function scopeGeneral($query)
    {
        return $query->where('type', 'general');
    }

    public function scopeProductPromo($query)
    {
        return $query->where('type', 'product');
    }

    // Methods
    public function isActive(): bool
    {
        return $this->is_active
            && $this->start_date <= now()
            && $this->end_date >= now();
    }

    public function getRemainingDays(): int
    {
        return now()->diffInDays($this->end_date, false);
    }
}
