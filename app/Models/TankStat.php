<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TankStat
 *
 * @property int $id
 * @property int $account_id
 * @property int $wargaming_id
 * @property string $type
 * @property int|null $wn8
 * @property int|null $wn8_30
 * @property string $mastery
 * @property int $battles
 * @property int $wins
 * @property int $losses
 * @property int $dropped_capture_points
 * @property int $capture_points
 * @property int $xp
 * @property int $frags
 * @property int $damage_dealt
 * @property int $spotted
 * @property int $battles_on_stunning_vehicles
 * @property int $survived_battles
 * @property int $hits_percents
 * @property int $draws
 * @property int $damage_received
 * @property int $stun_number
 * @property int $stun_assisted_damage
 * @property int $shots
 * @property int $hits
 * @property int $battle_avg_xp
 * @property string $date
 * @property-read \App\Models\Account $account
 * @method static TankStat whereAccountId($value)
 * @method static TankStat whereBattleAvgXp($value)
 * @method static TankStat whereBattles($value)
 * @method static TankStat whereBattlesOnStunningVehicles($value)
 * @method static TankStat whereCapturePoints($value)
 * @method static TankStat whereDamageDealt($value)
 * @method static TankStat whereDamageReceived($value)
 * @method static TankStat whereDate($value)
 * @method static TankStat whereDraws($value)
 * @method static TankStat whereDroppedCapturePoints($value)
 * @method static TankStat whereFrags($value)
 * @method static TankStat whereHits($value)
 * @method static TankStat whereHitsPercents($value)
 * @method static TankStat whereId($value)
 * @method static TankStat whereLosses($value)
 * @method static TankStat whereMastery($value)
 * @method static TankStat whereShots($value)
 * @method static TankStat whereSpotted($value)
 * @method static TankStat whereStunAssistedDamage($value)
 * @method static TankStat whereStunNumber($value)
 * @method static TankStat whereSurvivedBattles($value)
 * @method static TankStat whereType($value)
 * @method static TankStat whereWargamingId($value)
 * @method static TankStat whereWins($value)
 * @method static TankStat whereWn8($value)
 * @method static TankStat whereWn830($value)
 * @method static TankStat whereXp($value)
 * @mixin \Eloquent
 */
class TankStat extends Model
{
    protected $table = 'member_tank_stats';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'wargaming_id', 'type', 'wn8', 'mastery', 'max_xp', 'max_frags',
        'battles', 'wins', 'losses', 'dropped_capture_points', 'capture_points', 'xp', 'frags',
        'damage_dealt', 'spotted', 'battles_on_stunning_vehicles', 'survived_battles',
        'hits_percents', 'draws', 'damage_received', 'stun_number', 'stun_assisted_damage', 'shots',
        'hits', 'battle_avg_xp', 'date', 'account_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * Used when updating stats with WG data
     * WG values
     * 0 — None
     * 1 — 3rd Class
     * 2 — 2nd Class
     * 3 — 1st Class
     * 4 — Ace Tanker
     * @param integer $wgMastery
     */
    public function setMastery($wgMastery)
    {
        $map = ['false', '3', '2', '1', 'M'];
        $this->attributes['mastery'] = (string) $map[int($wgMastery)];
    }

    /**
     * Used when displaying
     * @return mixed
     */
    public function getMastery()
    {
        $map = ['false' => false, '3' => 3, '2' => 2, '1' => 1, 'M' => 'M'];
        return $map[$this->attributes['mastery']];
    }

    /**
     * @param Member $member
     * @param $data
     * @param bool $overwrite
     * @return TankStat
     */
    public static function createFromWargaming(Tank $tank, $data, $overwrite = false)
    {
        $tankStat = null;
        if ($overwrite) {
            $tankStat = TankStat::where([
                'wargaming_id' => $tank->wargaming_id,
                'date', '>=', today()
            ])->first();
        }
        $data['date'] = date('Y-m-d H:i:s');
        $data['account_id'] = $tank->account->id;
        $data['wargaming_id'] = $tank->wargaming_id;

        if (! is_null($tankStat)) {
            $tankStat->update($data);
        } else {
            $tankStat = TankStat::create($data);
        }

        return $tankStat;
    }
    public static function updateStats(Tank $tank, $data, $usedExtra, $overwrite = false)
    {
        $mastery = $data['mastery'];
        $data = self::prepareData($data, $usedExtra);

        foreach ($data as $set) {
            $set['mastery'] = $mastery;
            TankStat::createFromWargaming($tank, $set, $overwrite);
        }
    }
    /**
     * Prepares the data array for creating tank-stat records
     * @param $data
     * @param $usedExtra
     * @return array
     */
    private static function prepareData($data, $usedExtra)
    {
        $result = [];
        $keep = array_merge(['all'], $usedExtra);

        foreach ($data as $key => $entry) {
            if (in_array($key, $keep)) {
                $temp = $entry;
                $temp['type'] = $key;
                $result[] = $temp;
            }
        }

        return $result;
    }
}
