<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE users SET nik = NULLIF(TRIM(nik), ''), nuptk = NULLIF(TRIM(nuptk), ''), nbm = NULLIF(TRIM(nbm), '')");

        DB::statement('ALTER TABLE users MODIFY nik VARCHAR(16) NULL');
        DB::statement('ALTER TABLE users MODIFY nuptk VARCHAR(16) NULL');
        DB::statement('ALTER TABLE users MODIFY nbm VARCHAR(20) NULL');
        DB::statement('ALTER TABLE users MODIFY nomor_hp VARCHAR(15) NULL');
        DB::statement('ALTER TABLE users MODIFY jabatan VARCHAR(100) NULL');
        DB::statement('ALTER TABLE users MODIFY pasfoto VARCHAR(255) NULL');

        $adaIndex = function (string $nama) {
            return (bool) DB::selectOne(
                'SELECT COUNT(*) AS c FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ?',
                ['users', $nama]
            )->c;
        };

        Schema::table('users', function (Blueprint $table) use ($adaIndex) {
            if (! $adaIndex('users_role_index')) {
                $table->index('role', 'users_role_index');
            }
            if (! $adaIndex('users_nama_index')) {
                $table->index('nama', 'users_nama_index');
            }
        });

        // Unique hanya jika tidak ada duplikat non-null
        $duplikatNik = (bool) DB::selectOne("SELECT COUNT(*) AS c FROM (SELECT nik FROM users WHERE nik IS NOT NULL GROUP BY nik HAVING COUNT(*) > 1) t")->c;
        $duplikatNuptk = (bool) DB::selectOne("SELECT COUNT(*) AS c FROM (SELECT nuptk FROM users WHERE nuptk IS NOT NULL GROUP BY nuptk HAVING COUNT(*) > 1) t")->c;
        $duplikatNbm = (bool) DB::selectOne("SELECT COUNT(*) AS c FROM (SELECT nbm FROM users WHERE nbm IS NOT NULL GROUP BY nbm HAVING COUNT(*) > 1) t")->c;

        Schema::table('users', function (Blueprint $table) use ($duplikatNik, $duplikatNuptk, $duplikatNbm, $adaIndex) {
            if (! $duplikatNik && ! $adaIndex('users_nik_unique')) {
                $table->unique('nik', 'users_nik_unique');
            }
            if (! $duplikatNuptk && ! $adaIndex('users_nuptk_unique')) {
                $table->unique('nuptk', 'users_nuptk_unique');
            }
            if (! $duplikatNbm && ! $adaIndex('users_nbm_unique')) {
                $table->unique('nbm', 'users_nbm_unique');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndexIfExists('users_role_index');
            $table->dropIndexIfExists('users_nama_index');
            $table->dropUniqueIfExists('users_nik_unique');
            $table->dropUniqueIfExists('users_nuptk_unique');
            $table->dropUniqueIfExists('users_nbm_unique');
        });

        DB::statement('ALTER TABLE users MODIFY nik VARCHAR(255) NULL');
        DB::statement('ALTER TABLE users MODIFY nuptk VARCHAR(255) NULL');
        DB::statement('ALTER TABLE users MODIFY nbm VARCHAR(255) NULL');
        DB::statement('ALTER TABLE users MODIFY nomor_hp VARCHAR(255) NULL');
        DB::statement('ALTER TABLE users MODIFY jabatan VARCHAR(255) NULL');
    }
};
