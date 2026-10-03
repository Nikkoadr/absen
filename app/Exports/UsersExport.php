<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    public function collection(): Enumerable
    {
        return User::all();
    }
}
