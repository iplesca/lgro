
<?php

namespace WgApi;

use Illuminate\Support\Facades\Facade;

class WgApiFacade extends Facade
{
    protected static function getFacadeAccessor()
    { 
        return 'WotApi'; 
    }
}