<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nik', 16)->nullable()->unique();
            $table->string('nuptk', 16)->nullable()->unique();
            $table->string('nbm', 20)->nullable()->unique();
            $table->string('nomor_hp', 15)->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->time('jam_kerja')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
