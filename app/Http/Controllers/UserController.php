<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $data_user = User::latest()->paginate(100);;
        return view('data_user', compact('data_user'));
    }
}
