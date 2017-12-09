<?php
namespace Isteam\Wargaming;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class WargamingProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        if (Auth::check()) {
            $this->app['Isteam\Wargaming\Api']->setAccessToken(User::getAccessToken(Auth::user()));
        }
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Isteam\Wargaming\Api', function($app) {
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
        return ['Isteam\Wargaming\Api', 'Isteam\\Wargaming\\Api'];
    }
}