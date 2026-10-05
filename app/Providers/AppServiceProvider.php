<?php

namespace App\Providers;

use App\Models\Absensi;
use App\Models\Holiday;
use App\Models\Karyawan;
use App\Models\Kelas;
use App\Models\KompetensiKeahlian;
use App\Models\LeaveRequest;
use App\Models\Perangkat;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\Siswa;
use App\Models\User;
use App\Observers\AuditObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ([Absensi::class, Holiday::class, Karyawan::class, Kelas::class, KompetensiKeahlian::class, LeaveRequest::class, Perangkat::class, Setting::class, Shift::class, ShiftAssignment::class, Siswa::class, User::class] as $model) {
            $model::observe(AuditObserver::class);
        }
    }
}
