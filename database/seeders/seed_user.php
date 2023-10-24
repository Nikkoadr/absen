<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class seed_user extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'admin@smkmuhkandanghaur.sch.id',
            'password' => Hash::make('P4ssw0rd'),
            'nama' => 'Administrator',
            'status' => 'guru',

        ]);
    }
}
