<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->time('jam_masuk')->default('07:00:00')->after('wali_kelas_user_id');
            $table->time('jam_pulang')->nullable()->after('jam_masuk');
        });

        Schema::table('setting', function (Blueprint $table) {
            $table->string('telegram_bot_token', 100)->nullable()->after('limit_absen');
            $table->boolean('telegram_aktif')->default(false)->after('telegram_bot_token');
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn(['jam_masuk', 'jam_pulang']);
        });

        Schema::table('setting', function (Blueprint $table) {
            $table->dropColumn(['telegram_bot_token', 'telegram_aktif']);
        });
    }
};
