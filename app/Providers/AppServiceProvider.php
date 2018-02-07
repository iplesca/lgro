<?php

namespace App\Providers;

use App\Models\Clan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!defined('CLAN_ID')) {
            define('CLAN_ID', -1);
        }
        Schema::defaultStringLength(191);
        View::share('wotLogin', $this->app['Isteam\Wargaming\Api']->getLoginUrl());
        View::share('clanData', $this->getClanData());

    }
    private function getClanData()
    {
        if (-1 != CLAN_ID) {
            return Clan::getByWargamingId(CLAN_ID);
        }
        return false;
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        // ...
    }
}
