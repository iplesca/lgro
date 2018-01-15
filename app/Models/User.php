<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function membership()
    {
        return $this->belongsTo('App\Models\Member', 'member_id', 'id');
    }

    public static function getAccessToken(User $user)
    {
        return $user->wot_token;
    }
    public static function createFromWargaming($auth, $data)
    {
        $user = User::with('membership')->where('wargaming_id', $data['account_id'])->first();

        if (! $user) {
            $user = new User();
            $user->first = true;
            $user->nickname = $data['nickname'];
            $user->wargaming_id = $data['account_id'];
            $user->wot_created_at = date('Y-m-d H:i:s', $data['created_at']);
        }

        $user->wot_token = $auth['access_token'];
        $user->wot_token_expire = date('Y-m-d H:i:s', $auth['expires_at']);
        $user->wot_language = $data['client_language'];
        $user->wot_updated_at = date('Y-m-d H:i:s', $data['updated_at']);
        $user->save();

        return $user;
    }
    public function findAndAttachMembership(User $user)
    {
        $member = Member::where('wargaming_id', $user->wargaming_id)->first();

        if (! is_null($member)) {
            $user->membership()->associate($member);
            $user->save();
            $user->refresh();
            return true;
        }
        return false;
    }
    public static function getByWargamingId($userId)
    {
        $result = self::with('membership')->where('wargaming_id', $userId)->first();
        return $result;
    }
}
