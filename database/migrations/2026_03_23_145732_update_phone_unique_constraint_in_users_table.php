<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropUnique('users_phone_unique');
            } catch (\Throwable $e) {
            }

            $table->unique(['country_code', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropUnique(['country_code', 'phone']);
            } catch (\Throwable $e) {
            }

            $table->unique('phone');
        });
    }
};
