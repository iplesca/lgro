<?php

namespace App\Http\Controllers;

use App\Clan;
use App\Member;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Isteam\Wargaming\Api;

class Login extends Controller
{
    public function wargaming($wgCsrf, Request $request, Api $wgApi)
    {
        if (session('wgAuth') == $wgCsrf) {
            $auth = $request->all();

            if ('ok' == $auth['status']) {

                $user = User::getByWargamingId($auth['account_id']);
                if (!$user) {
                    // get latest user data
                    $userData = $wgApi->tanks()->getUserData($auth['account_id'], $auth['access_token']);
                    $user = User::createFromWargaming($auth, $userData);
                }

                if ($user) {
                    if ($user->can('access')) {
                        $this->doLogin($user);
                    } else {
                        $this->refreshWgCsrf(true);
                        $request->session()->flash('pop_message', 'Acces permis doar membrilor de clan');
                    }
                }
                /*
                // check if the user is a clan member
                $member = Member::getByWargamingId($auth['account_id']);

                if (! is_null($member)) {

                    // check member association
                    if (! $member->user()) {
                        $user->membership()->associate($member);
                        $member->user()->save($user);
                        $member->save();
                    }

                    Auth::login($user, true);
                } else {
                    $request->session()->flash('pop_message', 'Acces permis doar membrilor de clan');
                    return redirect('');
                }
                $this->refreshWgCsrf(true);
                return redirect('profile');
                */
            } else {
                // @todo log error
                // WG communication error
            }
        } else {
            // @todo log error
            // csrf mismatch
        }
        return redirect('');
    }
    private function doLogin(User $user)
    {
        Auth::login($user, true);
        return redirect('profile');
    }
    public function logout()
    {
        Auth::logout();
        return redirect('');
    }
}
