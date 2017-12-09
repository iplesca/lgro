<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public $timestamps = false;

    public function clan()
    {
        return $this->belongsTo('App\Clan');
    }
    public function user()
    {
        return $this->hasOne('App\User');
    }
    public static function getByWargamingId($id)
    {
        return static::where('wargaming_id', $id)->first();
    }
    public static function add(array $memberData, Clan $clan)
    {
        $new = new Member();
        $new->wargaming_id = $memberData['account_id'];
        $new->role = $memberData['role'];
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
        if ($new->wargaming_id == 519931899) {
            $a = 1;
        }
        if ($user) {
//            $user->membership()->associate($new);
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
        $history->days = Carbon::create($this->left)->diffInDays($history->joined);

        $history->save();
        $this->user()->dissociate();

        $this->delete();
    }
}
