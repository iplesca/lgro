<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public $timestamps = false;
    protected $casts = [
        'stats' => 'array',
    ];
    public function clan()
    {
        return $this->belongsTo('App\Models\Clan');
    }
    public function user()
    {
        return $this->hasOne('App\Models\User', 'user_id', 'id');
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
