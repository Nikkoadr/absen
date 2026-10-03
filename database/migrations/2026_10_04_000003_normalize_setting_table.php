<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bersihkan baris ganda agar singleton terjaga; simpan id terkecil
        $ganda = DB::table('setting')->orderBy('id')->get();
        if ($ganda->count() > 1) {
            $simpan = $ganda->first()->id;
            DB::table('setting')->where('id', '<>', $simpan)->delete();
        }

        DB::statement('ALTER TABLE setting MODIFY namaLokasi VARCHAR(150) NOT NULL');

        // Konversi aman string -> numerik; baris tak valid diganti default sebelum MODIFY
        DB::statement("UPDATE setting SET latitude = '-6.363041' WHERE latitude NOT REGEXP '^-?[0-9]+(\\.[0-9]+)?$'");
        DB::statement("UPDATE setting SET longitude = '108.113627' WHERE longitude NOT REGEXP '^-?[0-9]+(\\.[0-9]+)?$'");
        DB::statement("UPDATE setting SET radius = '70' WHERE radius NOT REGEXP '^[0-9]+$'");
        DB::statement('ALTER TABLE setting MODIFY latitude DECIMAL(10,7) NOT NULL');
        DB::statement('ALTER TABLE setting MODIFY longitude DECIMAL(10,7) NOT NULL');
        DB::statement('ALTER TABLE setting MODIFY radius INT UNSIGNED NOT NULL');

        Schema::table('setting', function (Blueprint $table) {
            $table->renameColumn('namaLokasi', 'nama_lokasi');
        });
    }

    public function down(): void
    {
        Schema::table('setting', function (Blueprint $table) {
            $table->renameColumn('nama_lokasi', 'namaLokasi');
        });

        DB::statement('ALTER TABLE setting MODIFY namaLokasi VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE setting MODIFY latitude VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE setting MODIFY longitude VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE setting MODIFY radius VARCHAR(255) NOT NULL');
    }
};
