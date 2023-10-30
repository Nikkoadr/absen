<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->string('id_user');
            $table->date('tanggal_absen');
            $table->time('jam_masuk');
            $table->time('jam_keluar')->nullable();
            $table->string('foto_masuk');
            $table->string('foto_keluar')->nullable();
            $table->text('lokasi_masuk');
            $table->text('lokasi_keluar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
