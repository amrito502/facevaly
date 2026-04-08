<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('order_number')->unique()->nullable();

            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);

            $table->unsignedBigInteger('tax_rate_id')->nullable();
            $table->decimal('tax', 10, 2)->default(0);

            $table->foreignId('shipping_rate_id')->nullable()->constrained('shipping_rates')->nullOnDelete();
            $table->decimal('shipping_cost', 10, 2)->default(0);

            $table->decimal('total', 10, 2)->default(0);

            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->text('full_address')->nullable();

            $table->enum('payment_method', ['cod', 'sslcommerz'])->default('cod');
            $table->enum('payment_status', ['unpaid', 'paid', 'failed'])->default('unpaid');

            $table->enum('status', ['approved', 'pending', 'delivered', 'canceled'])->default('pending');

            $table->date('delivery_date')->nullable();
            $table->date('canceled_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
