<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
    public function wargaming($wgCsrf, Request $request)
    {
        if (session('wgAuth') == $wgCsrf) {
            $auth = $request->all();

            if ('ok' == $auth['status']) {
                // check if the user is a clan member
                $wgData = App('WotApi')->getUserData($auth['account_id'], $auth['access_token']);

                if (env('CLAN_ID') == $wgData['clan_id']) {
                    $user = User::createFromWargaming($auth, $wgData);

                    Auth::login($user, true);
                } else {

                }
                $this->refreshWgCsrf(true);
                return redirect('profile');
            } else {
                return redirect('');
            }


        } else {
            // @todo log error
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('');
    }
}
