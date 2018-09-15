<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TankDefinition
 *
 * @property int $wargaming_id
 * @property string $nation
 * @property int $tier
 * @property string $type
 * @property string $name
 * @property string $name_short
 * @property string $name_uri
 * @property string $premium
 * @property string $image
 * @property string $image_small
 * @property string $image_contour
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereImageContour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereImageSmall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereNameShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereNameUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereNation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition wherePremium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereTier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereWargamingId($value)
 * @mixin \Eloquent
 */
class TankDefinition extends Model
{
    protected $table = 'wg_tanks';
    protected $primaryKey = 'wargaming_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'wargaming_id', 'nation', 'tier', 'tier', 'type', 'name', 'name_short',
        'name_uri', 'premium', 'image', 'image_small', 'image_contour'
    ];
    public static function getType($wgType)
    {
        $types = [
            'lightTank' => 'LT',
            'mediumTank' => 'MT',
            'heavyTank' => 'HT',
            'AT-SPG' => 'TD',
            'SPG' => 'SPG',
            'error' => 'error',
        ];

        return isset($types[$wgType]) ? $types[$wgType] : 'error';
    }
}
