<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AccountsHistory
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereClanWargamingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereJoined($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereWargamingId($value)
 * @mixin \Eloquent
 * @property int $account_id
 * @property-read \App\Models\Account $account
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereAccountId($value)
 */
class AccountsHistory extends Model
{
    public $timestamps = false;
    protected $table ="accounts_history";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
