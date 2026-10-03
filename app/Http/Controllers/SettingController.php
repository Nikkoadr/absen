<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    public function setting()
    {
        Gate::authorize('is_admin');

        $setting = Setting::first();

        return view('setting', compact('setting'));
    }

    public function editSetting(Request $request)
    {
        Gate::authorize('is_admin');

        $data_valid = $request->validate([
            'namaLokasi' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['required', 'integer', 'min:1', 'max:100000'],
            'limit_absen' => ['required', 'date_format:H:i,H:i:s'],
        ]);

        $setting = Setting::firstOrFail();
        $setting->update($data_valid);

        return redirect('setting')->with('success', 'Data Berhasil di Update');
    }
}
