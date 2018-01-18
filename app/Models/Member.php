<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Member
 *
 * @property int $id
 * @property int $first
 * @property int $clan_id
 * @property int|null $user_id
 * @property int $wargaming_id
 * @property int|null $wn8
 * @property int|null $wn8_30
 * @property string $nickname
 * @property string $role
 * @property string $granted
 * @property string|null $joined
 * @property int|null $score
 * @property int|null $premium
 * @property string|null $premium_expire
 * @property int|null $credits
 * @property int|null $gold
 * @property int|null $bonds
 * @property int|null $free_xp
 * @property int|null $ban_time
 * @property string|null $ban_info
 * @property int|null $phone_link
 * @property int|null $battle_time
 * @property string|null $logout
 * @property array $stats
 * @property-read \App\Models\Clan $clan
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereBanInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereBanTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereBattleTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereBonds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereClanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereCredits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereFreeXp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereGold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereGranted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereJoined($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereLogout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member wherePhoneLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member wherePremium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member wherePremiumExpire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereStats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereWargamingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereWn8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereWn830($value)
 * @mixin \Eloquent
 */
class Member extends Model
{
    public $timestamps = false;
    protected $casts = [
        'stats' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clan()
    {
        return $this->belongsTo('App\Models\Clan');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'member_id', 'id');
    }

    /**
     * @param $id
     * @return Member
     */
    public static function getByWargamingId($wargamingId)
    {
        return static::where('wargaming_id', $wargamingId)->first();
    }
    public static function add(array $memberData, Clan $clan)
    {
        $new = new Member();
        $new->wargaming_id = $memberData['account_id'];
        $new->role = $memberData['role'];
        $new->nickname = $memberData['account_name'];
        $new->granted = $memberData['role'];
        $new->joined = date('Y-m-d H:i:s', $memberData['joined_at']);
        $new->clan()->associate($clan);
        $new->save();

        return $new;
    }
    public static function readd(array $memberData, Clan $clan)
    {
        $new = self::add($memberData, $clan);
        $user = User::getByWargamingId($new->wargaming_id);

        if ($user) {
            $new->first = true;
            $new->user()->save($user);
            $user->membership()->save($new);
            $new->save();
        }
        return $new;
    }
    public function delete($reason = false)
    {
        $history = new MembershipHistory();
        if (false !== $reason) {
            $history->reason = $reason;
        }
        $history->wargaming_id = $this->wargaming_id;
        $history->joined = $this->joined;
        $history->left = date('Y-m-d H:i:s');
        $history->role = $this->role;
        $history->nickname = $this->nickname;
        $joined = Carbon::createFromFormat('Y-m-d H:i:s', $history->joined);
        $left = Carbon::createFromFormat('Y-m-d H:i:s', $history->left);
        $history->days = $left->diffInDays($joined);

        $history->save();
//        $this->user()->save(null);

        parent::delete();
    }
    public function updatePrivateData($data)
    {
        if (!empty($data)) {
            $this->score = $data['global_rating'];
            $this->logout = date('Y-m-d H:i:s', $data['logout_at']);
            $this->gold = $data['private']['gold'];
            $this->free_xp = $data['private']['free_xp'];
            $this->ban_time = $data['private']['ban_time'];
            $this->ban_info = $data['private']['ban_info'];
            $this->phone_link = $data['private']['is_bound_to_phone'];
            $this->credits = $data['private']['credits'];
            $this->bonds = $data['private']['bonds'];
            $this->battle_time = $data['private']['battle_life_time'];
            $this->premium = $data['private']['is_premium'];
            $this->premium_expire = date('Y-m-d H:i:s', $data['private']['premium_expires_at']);
            $this->stats = $data['statistics']['all'];
            $this->save();
        }
    }
}
