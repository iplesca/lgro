<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function ofiter(User $user)
    {
        return ($user->membership->granted != 'private') ? true : false;
    }
}
