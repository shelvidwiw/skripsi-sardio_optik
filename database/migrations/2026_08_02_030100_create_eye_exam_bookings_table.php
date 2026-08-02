<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eye_exam_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->string('name');
            $table->string('whatsapp_number');
            $table->date('preferred_date');
            $table->string('preferred_time'); // slot string, e.g. "10:00 - 11:00"
            $table->text('note')->nullable();
            $table->string('status')->default('pending'); // pending, dikonfirmasi, selesai, dibatalkan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eye_exam_bookings');
    }
};
