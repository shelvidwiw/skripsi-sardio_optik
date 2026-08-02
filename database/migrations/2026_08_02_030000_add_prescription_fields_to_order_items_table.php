<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Resep manual — dipakai untuk kategori Lensa (sph + cyl + axis) dan Soflens (sph saja)
            $table->decimal('sph_right', 4, 2)->nullable()->after('subtotal');   // mata kanan (OD)
            $table->decimal('sph_left', 4, 2)->nullable()->after('sph_right');   // mata kiri (OS)
            $table->decimal('cyl_right', 4, 2)->nullable()->after('sph_left');
            $table->decimal('cyl_left', 4, 2)->nullable()->after('cyl_right');
            $table->unsignedSmallInteger('axis_right')->nullable()->after('cyl_left');
            $table->unsignedSmallInteger('axis_left')->nullable()->after('axis_right');

            // Alternatif: upload foto resep dari dokter/optik, dibanding isi manual
            $table->string('prescription_file')->nullable()->after('axis_left');
            $table->text('prescription_note')->nullable()->after('prescription_file');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn([
                'sph_right', 'sph_left', 'cyl_right', 'cyl_left',
                'axis_right', 'axis_left', 'prescription_file', 'prescription_note',
            ]);
        });
    }
};
