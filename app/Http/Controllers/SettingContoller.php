<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;


class SettingContoller extends Controller
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
    public function setting()
    {
        if (Auth::user()->role == 'admin') {
            $setting = Setting::first();
            return view('setting', compact('setting'));
        }
    }
    public function editSetting(Request $request)
    {
        $data_valid = $request->validate([
            'namaLokasi' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
            'radius' => ['required'],
        ]);
        $setting = Setting::find($request->id);
        $setting->update($data_valid);
        return redirect('setting')->with('success', 'Data Berhasil di Update');
    }
}
