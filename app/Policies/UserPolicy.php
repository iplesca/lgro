<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function isOfficer(User $user)
    {
        if ($this->isMember($user)) {
            return (!in_array($user->membership->granted, ['private', 'recruit', 'reservist'])) ? true : false;
        }
        return false;
    }
    public function isExecutiveOfficer(User $user)
    {
        if ($this->isMember($user)) {
            return ('executive_officer' == $user->membership->granted) ? true : false;
        }
        return false;
    }
    public function access(User $user)
    {
        return $this->isMember($user) || $this->specialCase($user);
    }
    private function isMember($user)
    {
        if ($user->membership && CLAN_ID == $user->membership->clan->wargaming_id) {
            return true;
        }
        return false;
    }
    private function specialCase($user)
    {
        // SirLucasIV
        if (519931899 == $user->wargaming_id) {
            return true;
        }
        return false;
    }
}
