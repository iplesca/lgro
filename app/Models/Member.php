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
 * @property int|null $account_id
 * @property int $online
 * @property int $hibernate
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TankStat[] $tankStats
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tank[] $tanks
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereHibernate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereUpdatedAt($value)
 */
class Member extends Model
{
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
    public function account()
    {
        return $this->hasOne('App\Models\Account');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'member_id')->withDefault([
            'wargaming_id' => 0,
            'nickname' => 'AutoBot',
            'wot_language' => 'en',
            'wot_token' => date('Y-m-d H:i:s', strtotime('-1 day')),
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tanks()
    {
        return $this->hasManyThrough('App\Models\Tank', 'App\Models\Account');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tankStats()
    {
        return $this->hasManyThrough('App\Models\TankStat', 'App\Models\Account');
    }

    /**
     * Fetch all stats for a given tank WarGaming ID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function tankStatsById()
    {
        return $this->tankStats()->groupBy('wargaming_id')->get();
    }

    /**
     * Return member by WarGaming ID
     * @param $wargamingId
     * @return Member
     */
    public static function getByWargamingId($wargamingId)
    {
        return static::where('wargaming_id', $wargamingId)->first();
    }
    public static function createFromWargaming(array $memberData, Clan $clan)
    {
        $member = new Member();
        $member->wargaming_id = $memberData['account_id'];
        $member->role = $memberData['role'];
        $member->nickname = $memberData['account_name'];
        $member->granted = $memberData['role'];
        $member->joined = date('Y-m-d H:i:s', $memberData['joined_at']);
        $member->clan()->associate($clan);
        $member->save();

        return $member;
    }

    /**
     * @param array $memberData
     * @param Clan $clan
     * @return array [
     *      'isNew' => fresh account, should import tanks
     *      'member' => Member $member
     * ]
     */
    public static function reinstate(array $memberData, Clan $clan)
    {
        $newAccount = false;
        $member = self::createFromWargaming($memberData, $clan);

        $account = Account::getByWargamingId($member->wargaming_id);

        if (is_null($account)) {
            $newAccount = true;
            $account = Account::create([
                'wargaming_id' => $member->wargaming_id,
            ]);
        }

        $member->account()->save($account);
        $account->member()->save($member);

        $user = User::getByWargamingId($member->wargaming_id);

        if ($user) {
            $member->first = true;
            $member->user()->save($user);
            $user->membership()->save($member);
            $member->save();
        }
        return [
            'isNew' => $newAccount,
            'member' => $member
        ];
    }

    /**
     * Overloaded method.
     * Deletes one member and updates account and history
     * @param bool $reason
     * @return bool|null
     * @throws \Exception
     */
    public function delete($reason = false)
    {
//        $account = $this->account;
//        $account->member_id = null;
//        $account->member()->dissociate();
//        $account->save();

        $history = new AccountsHistory();
        if (false !== $reason) {
            $history->reason = $reason;
        }
        if (is_null($this->account)) {
            print_r($this->toArray());exit;
        }
        $history->clan_wargaming_id = $this->wargaming_id;
        $history->account_id = $this->account()->first()->id;
        $history->joined = $this->joined;
        $history->left = date('Y-m-d H:i:s');
        $history->role = $this->role;
        $history->nickname = $this->nickname;
        $joined = Carbon::createFromFormat('Y-m-d H:i:s', $history->joined);
        $left = Carbon::createFromFormat('Y-m-d H:i:s', $history->left);
        $history->days = $left->diffInDays($joined);

        $history->save();

        parent::delete();
    }

    /**
     * Update member statistics
     * @param $data
     * @param bool $hasPrivate
     */
    public function updateStats($data, $hasPrivate = false)
    {
        if (!empty($data)) {
            $this->stats = $data['statistics']['all'];
            $this->score = $data['global_rating'];
            $this->logout = date('Y-m-d H:i:s', $data['logout_at']);

            if ($hasPrivate) {
                $this->ban_time = $data['private']['ban_time'];
                $this->ban_info = $data['private']['ban_info'];
                $this->battle_time = $data['private']['battle_life_time'];
                $this->free_xp = $data['private']['free_xp'];
                $this->phone_link = $data['private']['is_bound_to_phone'];
                $this->credits = $data['private']['credits'];
                $this->gold = $data['private']['gold'];
                $this->bonds = $data['private']['bonds'];
                $this->premium = $data['private']['is_premium'];
                $this->premium_expire = date('Y-m-d H:i:s', $data['private']['premium_expires_at']);
            }

            $this->save();
        }
    }

    /**
     * Create member tanks from array
     * @param array $tanksData
     * @return array
     */
    public function createTanks(array $tanksData, $update = false)
    {
        $result = [];
        if (!empty($tanksData) && is_array($tanksData)) {
            foreach ($tanksData as $data) {
                $tank = Tank::createFromWargaming($this, $data, $update);
                $result[] = $tank->wargaming_id;
            }
        }

        return $result;
    }
    public function updateTankStats($tankData, $extra = [])
    {
        /**
         * @var Tank $tank
         */
        $tank = $this->tanks()->where('member_tanks.wargaming_id', $tankData['tank_id'])->first();
        if (!is_null($tank)) {
            $tank->addWargamingStatistics($tankData, $extra);
        } else {
            // @todo report missing tanks (rentals usually)
        }
    }

    /**
     * @param integer $tankId
     * @return Tank|mixed
     */
    public function getTankByWargamingId($tankId)
    {
        $tank = $this->tanks()->where([
            'account_id' => $this->account_id,
            'member_tanks.wargaming_id' => $tankId
        ])->first();

        return $tank;
    }
}
