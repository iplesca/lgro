<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property int|null $member_id
 * @property int $first
 * @property int $wargaming_id
 * @property string $nickname
 * @property string|null $name
 * @property string|null $email
 * @property string|null $password
 * @property string $wot_language
 * @property string $wot_token
 * @property string|null $wot_token_expire
 * @property string|null $wot_created_at
 * @property string|null $wot_updated_at
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Member|null $membership
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereWargamingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereWotCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereWotLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereWotToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereWotTokenExpire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereWotUpdatedAt($value)
 * @mixin \Eloquent
 */
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
        return $this->belongsTo(Member::class, 'member_id', 'id');
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
            $member->user()->save($user);
//            $member->save();
            $member->refresh();

            $user->membership()->associate($member);
            $user->save();

//            $user->refresh();
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
