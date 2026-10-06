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
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_absen');
            $table->time('jam_masuk');
            $table->time('jam_keluar')->nullable();
            $table->string('foto_masuk')->nullable();
            $table->string('foto_keluar')->nullable();
            $table->text('lokasi_masuk');
            $table->text('lokasi_keluar')->nullable();
            $table->timestamps();
            $table->unique(['id_user', 'tanggal_absen'], 'absensi_user_tanggal_unique');
            $table->index(['tanggal_absen', 'id_user'], 'absensi_tanggal_user_index');
            $table->index('jam_masuk', 'absensi_jam_masuk_index');
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
