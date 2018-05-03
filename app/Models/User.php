<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\Database\HasRolesAndAbilities;

/**
 * App\Models\User
 * Class User
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasRolesAndAbilities;

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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function membership()
    {
        return $this->hasOne(Member::class, 'user_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function account()
    {
        return $this->hasOne(Account::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tanks()
    {
        return $this->hasManyThrough('App\Models\TankDefinition', 'App\Models\Account');
    }

    /**
     * Returns the WarGaming access token
     * @param User $user
     * @return string
     */
    public static function getAccessToken(User $user)
    {
        return $user->wot_token;
    }

    /**
     * Create a new User from WarGaming data
     * @param $auth
     * @param $data
     * @return User|\Illuminate\Database\Eloquent\Model|null|static
     */
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

    /**
     * Searches for an existing Member with the same WarGaming ID and attaches it
     * @param User $user
     * @return bool
     */
    public function findAndAttachMembership(User $user)
    {
        $member = Member::where('wargaming_id', $user->wargaming_id)->first();

        if (! is_null($member)) {
            $member->user()->save($user);
            $member->save();
//            $member->refresh();

            $user->membership()->save($member);
            $user->save();

            $user->refresh();
            return true;
        }
        return false;
    }

    /**
     * Update user access (token and expire)
     * @param $data
     */
    public function updateAccess($data)
    {
        $this->wot_token = $data['access_token'];
        $this->wot_token_expire = date('Y-m-d H:i:s', $data['expires_at']);
        $this->assign($this->membership->granted);
        $this->loadPermissions();
        $this->save();
    }
    public function loadPermissions()
    {
        $ranks2roles = [
            "intelligence_officer" => ['member', 'officer'],
            "personnel_officer" => ['member', 'officer', 'personnel'],
            "quartermaster" => ['member', 'officer'],
            "executive_officer" => ['member', 'officer', 'executive'],
            "recruit" => ['member'],
            "private" => ['member'],
            "commander" => ['member', 'officer', 'admin'],
            "reservist" => ['member'],
            "combat_officer" => ['member', 'officer', 'combat'],
            "junior_officer" => ['member', 'officer'],
            "recruitment_officer" => ['member', 'officer', 'recruiter'],
        ];
        $granted = $this->membership->granted;
//        if ('admin' == $granted && '519931899' == $this->wargaming_id) {
//            $this->assign(['member', 'admin', 'superadmin']);
//            return;
//        }
        $this->assign($ranks2roles[$this->membership->granted]);
        \Bouncer::refreshFor($this);
    }

    /**
     * Return user by WarGaming ID
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public static function getByWargamingId($userId)
    {
        $result = self::with('membership')->where('wargaming_id', $userId)->first();
        return $result;
    }

    /**
     * Checks if the access token is valid.
     * [optional] Can set $afterMin to check validity from now
     * @param int $afterMin Shift the time limit upward
     * @return bool
     */
    public function isValidWotToken($afterMin = 1)
    {
        $result = false;
        if (!empty($this->wot_token_expire)) {
            $now = Carbon::now()->addMinutes($afterMin);
            $result = $this->wot_token_expire > $now ? true : false;
        }

        return $result;
    }
}
