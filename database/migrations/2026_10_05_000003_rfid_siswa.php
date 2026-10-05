<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('rfid_uid', 50)->nullable()->unique()->after('telegram_chat_id');
        });

        DB::statement('ALTER TABLE absensi MODIFY foto_masuk VARCHAR(255) NULL');
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropUniqueIfExists('siswas_rfid_uid_unique');
            $table->dropColumn('rfid_uid');
        });

        DB::statement('ALTER TABLE absensi MODIFY foto_masuk VARCHAR(255) NOT NULL');
    }
};
