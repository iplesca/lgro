<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function isOfficer(User $user)
    {
        return (! in_array($user->membership->granted, ['private', 'recruit', 'reservist'])) ? true : false;
    }
    public function isExecutiveOfficer(User $user)
    {
        return ('executive_officer' == $user->membership->granted) ? true : false;
    }
}
