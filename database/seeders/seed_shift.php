<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class seed_shift extends Seeder
{
    public function run()
    {
        Shift::firstOrCreate(['nama' => 'Pagi'], ['jam_masuk' => '07:00:00', 'jam_pulang' => '13:00:00']);
        Shift::firstOrCreate(['nama' => 'Siang'], ['jam_masuk' => '13:00:00', 'jam_pulang' => '19:00:00']);
    }
}
