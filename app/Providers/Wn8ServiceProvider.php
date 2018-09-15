<?php

namespace App\Providers;

use App\Wn8;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Wn8ServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Wn8::class, function ($app) {
            $wn8 = new Wn8();
            $wn8->setBaseData(File::getRequire(Storage::path($app['config']['isteam']['wn8Base'])));
            return $wn8;
        });
    }
    public function provides()
    {
        return Wn8::class;
    }
}
