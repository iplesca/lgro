<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Test extends Controller
{
    public function test()
    {
        $api = App('WotApi');
//        $result = $api->searchPlayerByNamePattern('sirlucasi');
        $result = $api->getUserData(519931899, '2cca557e2f685ade86cf124e9e741f317ff86881');
        if (!$result) {
            echo "<pre>";
            print_r($api->getErrors());
            echo "</pre>";
        } else {
            echo "<pre>";
//            var_dump(array_keys($result));
            print_r($result);
            echo "</pre>";
        }
        
        return view('welcome', ['var' => $result]);
    }
}
