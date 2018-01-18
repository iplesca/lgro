<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MembershipHistory
 *
 * @property int $id
 * @property int $wargaming_id
 * @property int $clan_wargaming_id
 * @property string $reason
 * @property string $nickname
 * @property string $role
 * @property string|null $joined
 * @property string|null $left
 * @property int $days
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MembershipHistory whereClanWargamingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MembershipHistory whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MembershipHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MembershipHistory whereJoined($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MembershipHistory whereLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MembershipHistory whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MembershipHistory whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MembershipHistory whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MembershipHistory whereWargamingId($value)
 * @mixin \Eloquent
 */
class MembershipHistory extends Model
{
    public $timestamps = false;
    protected $table ="membership_history";
}
