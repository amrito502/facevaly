<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('country_code', 10)->nullable()->after('id');
            $table->string('phone')->nullable()->unique()->after('country_code');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->string('full_name')->nullable()->after('name');
            $table->date('date_of_birth')->nullable()->after('full_name');
            $table->string('gender')->nullable()->after('date_of_birth');
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'country_code',
                'phone',
                'phone_verified_at',
                'full_name',
                'date_of_birth',
                'gender',
            ]);
        });
    }
};