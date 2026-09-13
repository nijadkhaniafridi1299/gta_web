<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CryptoPayment extends Model
{
    protected $fillable = [
        'order_id',
        'charge_id',
        'currency_code',
        'amount',
        'usd_amount',
        'wallet_address',
        'status',
        'confirmations',
        'payment_info',
        'transaction_hash',
        'confirmed_at',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'usd_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'expires_at' => 'datetime',
        'payment_info' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed' && $this->confirmed_at !== null;
    }
}
