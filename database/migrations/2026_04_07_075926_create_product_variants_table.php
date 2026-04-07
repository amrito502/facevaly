<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();

            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('regular_price', 12, 2)->nullable();
            $table->decimal('discounted_price', 12, 2)->nullable();
            $table->decimal('wholesale_price', 12, 2)->nullable();
            $table->unsignedInteger('wholesale_min_qty')->nullable();

            $table->unsignedInteger('stock_qty')->default(0);
            $table->decimal('weight_kg', 10, 3)->nullable();
            $table->string('image')->nullable();

            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['product_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};