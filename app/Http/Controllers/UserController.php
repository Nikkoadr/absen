<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;

class UserController extends Controller
{
    public function index()
    {
        $data_user = User::latest()->paginate(100);;
        return view('data_user', compact('data_user'));
    }

    public function importUser()
    {
        Excel::import(new UsersImport, request()->file('import'));
        return back();
    }
    public function exportUser()
    {
        return Excel::download(new UsersExport, 'data_users.xlsx');
    }
}
