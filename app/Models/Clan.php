<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Clan
 *
 * @property int $id
 * @property int $wargaming_id
 * @property string $name
 * @property string $tag
 * @property string $description
 * @property string $status
 * @property string $emblem32
 * @property string $emblem64
 * @property string $emblem195
 * @property string $color
 * @property string $motto
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Member[] $members
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereEmblem195($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereEmblem32($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereEmblem64($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereMotto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereWargamingId($value)
 * @mixin \Eloquent
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
    public static function getMembersByWargamingId($wargamingId)
    {
        return self::getByWargamingId($wargamingId)->members()->get();
    }
}
