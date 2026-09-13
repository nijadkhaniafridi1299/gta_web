<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_email',
        'subtotal',
        'discount',
        'total',
        'currency',
        'status',
        'payment_reference',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function keys(): HasMany
    {
        return $this->hasMany(GameKey::class);
    }

    public function cryptoPayments(): HasMany
    {
        return $this->hasMany(CryptoPayment::class);
    }

    public function latestCryptoPayment(): HasOne
    {
        return $this->hasOne(CryptoPayment::class)->latestOfMany();
    }

    public function isPaid(): bool
    {
        return in_array($this->status, ['paid', 'completed']) && $this->paid_at !== null;
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        if ($this->cryptoPayments()->exists() || (is_string($this->payment_reference) && (str_starts_with($this->payment_reference, 'chrg_') || str_starts_with($this->payment_reference, 'crypto_')))) {
            $crypto = $this->latestCryptoPayment;
            return $crypto ? 'Crypto (' . $crypto->currency_code . ')' : 'Cryptocurrency';
        }

        if (is_string($this->payment_reference) && str_starts_with($this->payment_reference, 'pi_')) {
            return 'Credit / Debit Card (Stripe)';
        }

        if (is_string($this->payment_reference) && (str_starts_with($this->payment_reference, 'PAYID-') || str_starts_with($this->payment_reference, 'EC-'))) {
            return 'PayPal';
        }

        return $this->payment_reference ? 'Online Payment' : 'Pending Payment';
    }

    public function restockKeys(): int
    {
        $count = 0;
        foreach ($this->keys as $key) {
            $key->update([
                'status' => 'available',
                'order_id' => null,
                'sold_at' => null,
            ]);
            $count++;
        }
        return $count;
    }
}
