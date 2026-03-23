<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('shop_name')->nullable()->after('gender');
            $table->string('owner_name')->nullable()->after('shop_name');
            $table->string('owner_phone')->nullable()->after('owner_name');
            $table->string('whatsapp_number')->nullable()->after('owner_phone');
            $table->string('referral_code')->nullable()->after('whatsapp_number');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'shop_name',
                'owner_name',
                'owner_phone',
                'whatsapp_number',
                'referral_code',
            ]);

        });
    }
};
