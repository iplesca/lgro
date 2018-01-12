<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tank extends Model
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
