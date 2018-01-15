<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clan extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany('App\Models\Member');
    }
    public static function getByWargamingId($wargamingId)
    {
        return self::where('wargaming_id', $wargamingId)->first();
    }
}
