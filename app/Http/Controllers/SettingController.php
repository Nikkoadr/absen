<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingRequest;
use App\Models\Setting;

class SettingController extends Controller
{
    public function setting()
    {
        $setting = Setting::firstOrCreate(
            ['id' => 1],
            [
                'nama_lokasi' => 'SMK Muhammadiyah Kandanghaur',
                'latitude' => '-6.363041',
                'longitude' => '108.113627',
                'radius' => '70',
                'limit_absen' => '13:00:00',
            ]
        );

        return view('setting', compact('setting'));
    }

    public function editSetting(UpdateSettingRequest $request)
    {
        Setting::firstOrFail()->update(
            array_merge(['telegram_aktif' => false], $request->validated())
        );

        return to_route('setting')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
