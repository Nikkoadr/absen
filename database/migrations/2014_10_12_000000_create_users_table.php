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
            $table->enum('role', ['admin', 'guru', 'siswa'])->default('siswa');
            $table->string('nik')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('nbm')->nullable();
            $table->string('nama');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('jabatan')->nullable();
            $table->time('jam_kerja')->nullable();
            $table->string('lokasi_lang')->nullable();
            $table->string('lokasi_long')->nullable();
            $table->string('pas_foto')->nullable();
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
