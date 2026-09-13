<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crypto_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('charge_id')->unique(); // Coinbase charge ID
            $table->string('currency_code'); // BTC, ETH, USDC, DOGE
            $table->decimal('amount', 16, 8); // Crypto amount
            $table->decimal('usd_amount', 10, 2); // USD equivalent
            $table->string('wallet_address')->nullable();
            $table->string('status')->default('pending'); // pending, confirmed, failed, expired
            $table->integer('confirmations')->default(0);
            $table->text('payment_info')->nullable(); // JSON data from Coinbase
            $table->string('transaction_hash')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->index('status');
            $table->index('charge_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_payments');
    }
};
