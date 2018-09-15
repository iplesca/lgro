<?php
namespace App\Models\Api;

use App\Models\Clan;

/**
 * This file is part of the isteam project.
 * 
 * Date: 15/01/18 07:31
 *
 * @author ionut
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Member[] $members
 */

class ClanQuery extends Clan
{
    public function sourceUpdate($data)
    {
        parent::update();
    }
}