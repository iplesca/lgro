<?php
namespace Isteam\Wargaming;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class WargamingProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
//        AliasLoader::getInstance()->alias('wg-api', 'Isteam\\Wargaming\\Api');
        View::share('wotLogin', $this->app['wg-api']->getLoginUrl());
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('wg-api', function($app) {
            $wgApi = new Api();
            $wgApi->setup($app['config']['wotapi']);
            
            return $wgApi;
        });
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['wg-api', 'Isteam\\Wargaming\\Api'];
    }
}