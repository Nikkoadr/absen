<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\User;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('is_admin', function (User $user) {
            return $user->role == 'admin';
        });
        Gate::define('is_karyawan', function (User $user) {
            return $user->role == 'karyawan';
        });
        Gate::define('is_guru', function (User $user) {
            return $user->role == 'guru';
        });
        Gate::define('is_siswa', function (User $user) {
            return $user->role == 'siswa';
        });
    }
}
