<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();

            $table->foreignId('shop_id')->nullable()->constrained('shops')->nullOnDelete();
            $table->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('sub_order_number')->unique();

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            // commission + markup + payout snapshot
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->decimal('commission_amount', 12, 2)->default(0);

            $table->decimal('markup_amount', 12, 2)->default(0);
            $table->decimal('gateway_charge', 12, 2)->default(0);

            $table->decimal('seller_payable', 12, 2)->default(0);
            $table->decimal('admin_earning', 12, 2)->default(0);

            $table->enum('status', [
                'pending',
                'approved',
                'processing',
                'delivered',
                'canceled'
            ])->default('pending');

            $table->date('delivery_date')->nullable();
            $table->date('canceled_date')->nullable();

            $table->timestamps();

            $table->index(['order_id']);
            $table->index(['shop_id', 'status']);
            $table->index(['seller_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_orders');
    }
};