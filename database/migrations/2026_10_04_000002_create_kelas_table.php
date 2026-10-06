<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kompetensi_id')->constrained('kompetensi_keahlian')->cascadeOnDelete();
            $table->string('nama', 50);
            $table->string('tingkat', 10);
            $table->foreignId('wali_kelas_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->time('jam_masuk')->default('07:00:00');
            $table->time('jam_pulang')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
