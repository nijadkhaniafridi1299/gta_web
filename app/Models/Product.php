<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'platform',
        'region',
        'edition',
        'image',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function keys(): HasMany
    {
        return $this->hasMany(GameKey::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getAvailableKeysCountAttribute(): int
    {
        return $this->keys()->where('status', 'available')->count();
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->available_keys_count > 0;
    }

    public function getOriginalPriceAttribute(): float
    {
        // 15% - 25% higher dummy list price for instant discount display
        return round($this->price * 1.25, 2);
    }

    public function getDiscountPercentAttribute(): int
    {
        return 20;
    }

    public function getRatingAttribute(): float
    {
        return 4.9;
    }

    public function getReviewsCountAttribute(): int
    {
        return 2840;
    }

    public function getDrmClientAttribute(): string
    {
        return match (strtolower($this->platform)) {
            'playstation', 'ps5' => 'PlayStation Network (PSN)',
            'xbox', 'xbox series x' => 'Xbox Live / Microsoft Store',
            default => 'Rockstar Games Launcher / Steam',
        };
    }
}
