<?php
namespace App\Administration;

/**
 * This file is part of the isteam project.
 *
 * Date: 20/01/18 14:31
 * @author ionut
 */
use Illuminate\Support\Facades\App;
use Isteam\Wargaming\Api;

class Base
{
    /**
     * @var Api $api
     */
    protected $api;

    public function __construct()
    {
        $this->api = App::make(Api::class);
        $this->init();
    }
    public function init()
    {
        // ... override in children
    }
}