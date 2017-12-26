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
                // check if the user is a clan member
                $member = Member::getByWargamingId($auth['account_id']);
                if (! is_null($member)) {
                    // get latest user data
                    $userData = $wgApi->tanks()->getUserData($auth['account_id'], $auth['access_token']);
                    if (env('CLAN_ID') == $userData['clan_id']) {
                        $user = User::createFromWargaming($auth, $userData);

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
