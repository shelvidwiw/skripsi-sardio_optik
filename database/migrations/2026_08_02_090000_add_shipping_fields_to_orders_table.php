<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Alamat pengiriman opsional (nullable) — dulu checkout cuma minta Nama + WhatsApp.
            // Ditambahkan supaya form checkout bisa menyamai desain referensi terbaru.
            $table->string('street_address')->nullable()->after('whatsapp_number');
            $table->string('city')->nullable()->after('street_address');
            $table->string('postal_code')->nullable()->after('city');
            $table->decimal('tax_amount', 12, 2)->default(0)->after('total_amount');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['street_address', 'city', 'postal_code', 'tax_amount']);
        });
    }
};
