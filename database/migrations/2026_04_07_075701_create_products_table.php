<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();
            $table->string('barcode')->nullable();

            $table->enum('product_type', ['single', 'variant'])->default('single');

            $table->longText('description')->nullable();
            $table->longText('specification')->nullable();

            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('regular_price', 12, 2)->nullable();
            $table->decimal('discounted_price', 12, 2)->nullable();

            $table->boolean('is_retail')->default(true);
            $table->boolean('is_wholesale')->default(false);
            $table->decimal('wholesale_price', 12, 2)->nullable();
            $table->unsignedInteger('wholesale_min_qty')->nullable();

            $table->unsignedInteger('stock_qty')->default(0);
            $table->decimal('weight_kg', 10, 3)->nullable();

            $table->enum('warranty_type', ['no_warranty', 'seller_warranty'])->default('no_warranty');
            $table->text('warranty_policy')->nullable();

            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->enum('tax_type', ['fixed', 'percent'])->default('percent');

            $table->enum('status', ['draft', 'pending', 'active', 'inactive', 'rejected'])->default('draft');
            $table->boolean('is_featured')->default(false);

            $table->unsignedBigInteger('view_count')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);

            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['shop_id', 'status']);
            $table->index(['category_id', 'status']);
            $table->index(['brand_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};