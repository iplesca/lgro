<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function refreshWgCsrf($do = false)
    {
        if ($do || ! session('wgAuth')) {
            session(['wgAuth' => md5(uniqid(time()))]);
        }
    }
    protected function useView($name, $data = array())
    {
        return view(ISTEAM_TEMPLATE . '.' . $name, $data);
    }
}
