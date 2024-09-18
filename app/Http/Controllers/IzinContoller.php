<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;


class IzinContoller extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function izin()
    {
        if (Auth::user()->role != 'admin') {
            return view('izin_mobile');
        }
    }
    public function request_izin_user()
    {
        echo "Maaf Dalam proses Development";
    }
}
