<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_warranties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['replacement', 'service']);
            $table->unsignedInteger('duration')->nullable();
            $table->enum('duration_unit', ['day', 'month', 'year'])->default('day');
            $table->timestamps();

            $table->unique(['product_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_warranties');
    }
};