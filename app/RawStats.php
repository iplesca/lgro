<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RawStats extends Model
{
    protected $table = 'raw_stats';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'json',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
