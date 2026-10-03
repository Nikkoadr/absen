<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Karantina baris yatim sebelum pasang FK (id_user tidak numerik / tidak ada di users)
        if (! Schema::hasTable('absensi_orphans')) {
            Schema::create('absensi_orphans', function (Blueprint $table) {
                $table->id();
                $table->string('id_user_asal');
                $table->date('tanggal_absen')->nullable();
                $table->time('jam_masuk')->nullable();
                $table->time('jam_keluar')->nullable();
                $table->string('foto_masuk')->nullable();
                $table->string('foto_keluar')->nullable();
                $table->text('lokasi_masuk')->nullable();
                $table->text('lokasi_keluar')->nullable();
                $table->timestamps();
            });
        }

        DB::statement("
            INSERT INTO absensi_orphans (id_user_asal, tanggal_absen, jam_masuk, jam_keluar, foto_masuk, foto_keluar, lokasi_masuk, lokasi_keluar, created_at, updated_at)
            SELECT a.id_user, a.tanggal_absen, a.jam_masuk, a.jam_keluar, a.foto_masuk, a.foto_keluar, a.lokasi_masuk, a.lokasi_keluar, NOW(), NOW()
            FROM absensi a
            LEFT JOIN users u ON u.id = CAST(a.id_user AS UNSIGNED)
            WHERE a.id_user NOT REGEXP '^[0-9]+$' OR u.id IS NULL
        ");
        DB::statement("
            DELETE a FROM absensi a
            LEFT JOIN users u ON u.id = CAST(a.id_user AS UNSIGNED)
            WHERE a.id_user NOT REGEXP '^[0-9]+$' OR u.id IS NULL
        ");

        // Hapus duplikat (user+tanggal) agar unique constraint bisa dipasang; simpan id terkecil
        $duplikat = DB::select('SELECT id_user, tanggal_absen, MIN(id) AS keep_id FROM absensi GROUP BY id_user, tanggal_absen HAVING COUNT(*) > 1');
        foreach ($duplikat as $d) {
            DB::delete('DELETE FROM absensi WHERE id_user = ? AND tanggal_absen = ? AND id <> ?', [$d->id_user, $d->tanggal_absen, $d->keep_id]);
        }

        DB::statement('ALTER TABLE absensi MODIFY id_user BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE absensi MODIFY foto_masuk VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE absensi MODIFY foto_keluar VARCHAR(255) NULL');

        Schema::table('absensi', function (Blueprint $table) {
            $table->timestamps();
            $table->foreign('id_user', 'absensi_id_user_foreign')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['id_user', 'tanggal_absen'], 'absensi_user_tanggal_unique');
            $table->index(['tanggal_absen', 'id_user'], 'absensi_tanggal_user_index');
            $table->index('jam_masuk', 'absensi_jam_masuk_index');
        });
    }

    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropForeign('absensi_id_user_foreign');
            $table->dropUnique('absensi_user_tanggal_unique');
            $table->dropIndex('absensi_tanggal_user_index');
            $table->dropIndex('absensi_jam_masuk_index');
            $table->dropTimestamps();
        });

        DB::statement('ALTER TABLE absensi MODIFY id_user VARCHAR(255) NOT NULL');
    }
};
