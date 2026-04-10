<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('sub_order_id')->constrained('sub_orders')->cascadeOnDelete();

            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('shop_id')->nullable()->constrained('shops')->nullOnDelete();
            $table->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete();

            $table->decimal('seller_price', 12, 2)->default(0);
            $table->decimal('sale_price', 12, 2)->default(0);

            $table->unsignedInteger('quantity')->default(1);

            $table->decimal('line_seller_total', 12, 2)->default(0);
            $table->decimal('line_sale_total', 12, 2)->default(0);

            $table->decimal('markup_amount', 12, 2)->default(0);

            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->decimal('commission_amount', 12, 2)->default(0);

            $table->decimal('seller_payable', 12, 2)->default(0);
            $table->decimal('admin_earning', 12, 2)->default(0);

            $table->longText('options')->nullable();

            // return status
            $table->boolean('rstatus')->default(false);

            $table->timestamps();

            $table->index(['order_id']);
            $table->index(['sub_order_id']);
            $table->index(['product_id']);
            $table->index(['shop_id']);
            $table->index(['seller_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};