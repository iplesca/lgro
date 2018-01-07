<?php

namespace App\Providers;

use App\Policies\UserPolicy;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isOfficer', 'App\Policies\UserPolicy@isOfficer');
        Gate::define('isCE', 'App\Policies\UserPolicy@isExecutiveOfficer');
        Gate::define('access', 'App\Policies\UserPolicy@access');
//        Gate::define('isOfficer', function (User $user) {
//            return ($user->membership->granted == 'private') ? true : false;
//        });
    }
}
