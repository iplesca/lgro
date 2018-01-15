<?php

namespace App\Http\Controllers;

use App\Models\Clan;
use App\Models\Member;
use App\Models\User;
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

                $userData = [];
                if (!$user) {
                    // get latest user data
                    $userData = $wgApi->tanks()->getUserData($auth['account_id'], $auth['access_token']);
                    $user = User::createFromWargaming($auth, $userData);
                }

                if ($user) {
                    // check if member
                    if (is_null($user->membership) && $user->findAndAttachMembership($user)) {
                        // @todo refactor/think of a clear model pattern
                        $user->membership->updatePrivateData($userData);
                    }

                    if ($user->can('access')) {
                        $this->doLogin($user);
                    } else {
                        $this->refreshWgCsrf(true);
                        // @todo Refactor: move elsewhere
                        $request->session()->flash('pop_message', 'Acces permis doar membrilor de clan');
                    }
                }
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
        if (! is_null($user->membership)) {
            return redirect('profile');
        }
        return redirect('profile/standard');
    }
    public function logout()
    {
        Auth::logout();
        return redirect('');
    }
}
