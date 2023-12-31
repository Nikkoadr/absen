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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['admin', 'karyawan', 'guru', 'siswa'])->default('siswa');
            $table->string('nik')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('nbm')->nullable();
            $table->string('nama');
            $table->string('nomor_hp')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('jabatan')->nullable();
            $table->time('jam_kerja')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->string('pasfoto')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
