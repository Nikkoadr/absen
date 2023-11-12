<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class seed_setting extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'namaLokasi' => 'SMK Muhammadiyah Kandanghaur',
            'latitude' => '-6.363041',
            'longitude' => '108.113627',
            'radius' => '70',

        ]);
    }
}
