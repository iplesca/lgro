<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Test extends Controller
{
    public function test()
    {
        $api = App('WotApi');
        $result = $api->searchPlayerByNamePattern('lucas');
        
        if (!$result) {
            echo "<pre>";
            print_r($api->getErrors());
            echo "</pre>";
        } else {
            echo "<pre>";
            print_r($result);
            echo "</pre>";
        }
        
        return view('welcome', ['var' => $result]);
    }
}
