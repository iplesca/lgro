<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Clan
 */
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
    public static function getByWargamingTag($wargamingTag)
    {
        return self::where('tag', $wargamingTag)->first();
    }
    public static function getMembersByWargamingId($wargamingId)
    {
        return self::getByWargamingId($wargamingId)->members()->get();
    }
}
