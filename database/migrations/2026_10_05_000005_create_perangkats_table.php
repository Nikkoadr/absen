<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perangkats', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('key_hash', 64)->unique();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamp('terakhir_aktif')->nullable();
            $table->timestamps();
        });

        Schema::table('absensi', function (Blueprint $table) {
            $table->foreignId('gerbang_id')->nullable()->after('id_user')->constrained('perangkats')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropConstrainedForeignId('gerbang_id');
        });

        Schema::dropIfExists('perangkats');
    }
};
