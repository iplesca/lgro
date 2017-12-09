<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clan extends Model
{

    public function members()
    {
        return $this->hasMany('App\Member');
    }

    public function getByWargamingId($id)
    {
        return self::where('wargaming_id', $id)->first();
    }
}
