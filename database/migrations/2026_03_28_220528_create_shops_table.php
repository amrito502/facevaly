<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();

            $table->string('shop_name');
            $table->string('slug')->unique();
            $table->string('website_url')->nullable();
            $table->text('address')->nullable();

            $table->string('shop_email')->nullable();
            $table->string('shop_phone', 30)->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->text('description')->nullable();

            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status', ['draft', 'active', 'suspended', 'closed'])->default('draft');

            // shop wise commission
            $table->decimal('commission_rate', 5, 2)->nullable();

            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();

            $table->index(['seller_id', 'status']);
            $table->index(['verification_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};