<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kompetensi_keahlian', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('singkatan', 10);
            $table->timestamps();
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kompetensi_id')->constrained('kompetensi_keahlian')->cascadeOnDelete();
            $table->string('nama', 50);
            $table->string('tingkat', 10);
            $table->foreignId('wali_kelas_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

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

        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->string('nis', 20)->nullable()->unique();
            $table->string('nisn', 20)->nullable()->unique();
            $table->string('nama_ortu', 100)->nullable();
            $table->string('nomor_hp_ortu', 15)->nullable();
            $table->string('telegram_chat_id', 50)->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->date('tanggal_lahir')->nullable()->after('email');
        });

        DB::statement(
            'INSERT INTO karyawans (user_id, nik, nuptk, nbm, nomor_hp, jabatan, jam_kerja, jam_pulang, created_at, updated_at)
             SELECT id, nik, nuptk, nbm, nomor_hp, jabatan, jam_kerja, jam_pulang, NOW(), NOW() FROM users WHERE role <> \'siswa\''
        );
        DB::statement(
            'INSERT INTO siswas (user_id, created_at, updated_at)
             SELECT id, NOW(), NOW() FROM users WHERE role = \'siswa\''
        );

        Schema::table('users', function (Blueprint $table) {
            $table->dropUniqueIfExists('users_nik_unique');
            $table->dropUniqueIfExists('users_nuptk_unique');
            $table->dropUniqueIfExists('users_nbm_unique');
            $table->dropColumn(['nik', 'nuptk', 'nbm', 'nomor_hp', 'jabatan', 'jam_kerja', 'jam_pulang']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 16)->nullable();
            $table->string('nuptk', 16)->nullable();
            $table->string('nbm', 20)->nullable();
            $table->string('nomor_hp', 15)->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->time('jam_kerja')->nullable();
            $table->time('jam_pulang')->nullable();
        });

        DB::statement(
            'UPDATE users u JOIN karyawans k ON k.user_id = u.id
             SET u.nik = k.nik, u.nuptk = k.nuptk, u.nbm = k.nbm, u.nomor_hp = k.nomor_hp,
                 u.jabatan = k.jabatan, u.jam_kerja = k.jam_kerja, u.jam_pulang = k.jam_pulang'
        );

        Schema::dropIfExists('siswas');
        Schema::dropIfExists('karyawans');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('kompetensi_keahlian');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tanggal_lahir');
        });
    }
};
