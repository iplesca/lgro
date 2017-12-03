<?php

namespace WgApi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class WgApiProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        AliasLoader::getInstance()->alias('WotApi', 'App\WgApi\WotApi');
        View::share('wotLogin', $this->app['WotApi']->getLoginUrl());
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('WotApi', function($app) {
            $apiConfig = $app['config']['wotapi'];
            $wgConfig = new WgClientConfig();
            $wgConfig->setRealm($apiConfig['default_realm']);
            $wgConfig->setApplicationId($apiConfig['application_id']);
            $wgConfig->setRedirectUri($apiConfig['redirect_uri']);

            $wotApi = new WotApi($wgConfig);
            return $wotApi;
        });
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['WotApi', 'App\WgApi\WotApi'];
    }
}
