<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $google = Socialite::driver('google')->user();

        if (! $google->getEmail()) {
            return to_route('login')->withErrors(['email' => 'Akun Google tidak memberikan alamat email.']);
        }

        $pengguna = User::where('google_id', $google->getId())->first()
            ?? User::where('email', $google->getEmail())->first();

        if ($pengguna) {
            abort_if(! $pengguna->aktif, 403, 'Akun dinonaktifkan. Hubungi admin sekolah.');
            $pengguna->update(['google_id' => $google->getId()]);
        } else {
            $pengguna = User::create([
                'nama' => $google->getName() ?: $google->getEmail(),
                'email' => $google->getEmail(),
                'google_id' => $google->getId(),
                'password' => Str::random(32),
                'role' => 'karyawan',
            ]);
        }

        Auth::login($pengguna, true);

        return to_route('home');
    }
}
