<?php
namespace App\Http\Controllers;

use App\Managers\ClanManager;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Isteam\Wargaming\Api;

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
        \View::share('wotLogin', $this->api()->getLoginUrl(ClanManager::getClanTag()));
        session()->save();
        return view(ISTEAM_TEMPLATE . '.' . $name, $data);
    }
    /**
     * @return Api
     */
    public function api()
    {
        return \App::get('Isteam\Wargaming\Api');
    }
}
