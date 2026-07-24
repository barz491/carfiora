<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'max_discount',
        'min_purchase',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopeValid($query)
    {
        return $query->active()->where(function ($q) {
            $q->whereNull('usage_limit')
              ->orWhereRaw('used_count < usage_limit');
        });
    }

    // Methods
    public function isValid(): bool
    {
        return $this->is_active
            && $this->start_date <= now()
            && $this->end_date >= now()
            && ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    public function calculateDiscount($amount): float
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($amount < $this->min_purchase) {
            return 0;
        }

        $discount = 0;

        if ($this->type === 'percentage') {
            $discount = ($amount * $this->value) / 100;
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
        } else {
            $discount = $this->value;
        }

        return (float) $discount;
    }
}
