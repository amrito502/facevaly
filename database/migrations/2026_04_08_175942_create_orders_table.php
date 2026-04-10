<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number')->unique();

            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);

            // tax optional
            $table->unsignedBigInteger('tax_rate_id')->nullable();
            $table->decimal('tax', 12, 2)->default(0);

            $table->foreignId('shipping_rate_id')->nullable()->constrained('shipping_rates')->nullOnDelete();
            $table->decimal('shipping_cost', 12, 2)->default(0);

            $table->decimal('total', 12, 2)->default(0);

            $table->string('name')->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('full_address')->nullable();

            $table->enum('payment_method', ['cod', 'sslcommerz'])->default('cod');
            $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'refunded'])->default('unpaid');

            $table->enum('status', [
                'pending',
                'processing',
                'approved',
                'partial_delivered',
                'delivered',
                'partial_canceled',
                'canceled'
            ])->default('pending');

            $table->date('delivery_date')->nullable();
            $table->date('canceled_date')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};