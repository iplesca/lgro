<?php

namespace App;

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

    public function membership()
    {
        return $this->belongsTo('App\Member', 'member_id');
    }
    public function stats()
    {
        return $this->hasOne('App\RawStats');
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
            $user->nickname = $data['nickname'];
            $user->wargaming_id = $data['account_id'];

            $stats = new RawStats();

        } else {
            // update stats
            $stats = $user->stats()->first();
        }

        $user->wot_token = $auth['access_token'];
        $user->wot_token_expire = date('Y-m-d H:i:s', $auth['expires_at']);

        $user->wot_rating = $data['global_rating'];
        $user->wot_language = $data['client_language'];
        $user->wot_logout = date('Y-m-d H:i:s', $data['logout_at']);
        $user->wot_created_at = date('Y-m-d H:i:s', $data['created_at']);
        $user->wot_updated_at = date('Y-m-d H:i:s', $data['updated_at']);

        // private
        $user->wot_gold = $data['private']['gold'];
        $user->wot_free_xp = $data['private']['free_xp'];
        $user->wot_ban_time = $data['private']['ban_time'];
        $user->wot_ban_info = $data['private']['ban_info'];
        $user->wot_phone_link = $data['private']['is_bound_to_phone'];
        $user->wot_credits = $data['private']['credits'];
        $user->wot_bonds = $data['private']['bonds'];
        $user->wot_battle_time = $data['private']['battle_life_time'];
        $user->wot_premium = $data['private']['is_premium'];
        $user->wot_premium_expire = date('Y-m-d H:i:s', $data['private']['premium_expires_at']);
        $user->clan_id = -1;

        $user->save();

        $stats->json = json_encode($data['statistics']);
        $stats->user()->associate($user);
        $stats->save();

        return $user;
    }
    public static function getByWargamingId($id)
    {
        return self::where('wargaming_id', $id)->first();
    }
}
