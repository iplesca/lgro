<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ClanMeta
 */
class ClanMeta extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function clan()
    {
        return $this->hasOne('App\Models\Clan');
    }
    public static function getByWargamingId($wargamingId)
    {
        return self::where('wargaming_id', $wargamingId)->first();
    }
}
