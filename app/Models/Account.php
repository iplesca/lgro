<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Account
 *
 * @property int $id
 * @property int|null $member_id
 * @property int|null $user_id
 * @property int $wargaming_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AccountsHistory[] $history
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereWargamingId($value)
 * @mixin \Eloquent
 */
class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'wargaming_id'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function member()
    {
        return $this->hasOne('App\Models\Member');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
//        return $this->hasOne(User::class, 'user_id')->withDefault([
//            'wargaming_id' => 0,
//            'nickname' => 'AutoBot',
//            'wot_language' => 'en',
//            'wot_token' => date('Y-m-d H:i:s', strtotime('-1 day')),
//        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany(AccountsHistory::class, 'account_id');
    }

    /**
     * @param $wargamingId
     * @return Account
     */
    public static function getByWargamingId($wargamingId)
    {
        return static::where('wargaming_id', $wargamingId)->first();
    }
}
