<?php

namespace App\Models;

use App\Wn8;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * App\Models\Tank
 *
 * @property int $id
 * @property int $account_id
 * @property int $wargaming_id
 * @property string $updated
 * @property int $in_garage
 * @property int|null $wn8
 * @property int|null $wn8_30
 * @property string $mastery
 * @property int $max_xp
 * @property int $max_frags
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
 * @property-read \App\Models\Account $account
 * @method static Tank whereAccountId($value)
 * @method static Tank whereBattleAvgXp($value)
 * @method static Tank whereBattles($value)
 * @method static Tank whereBattlesOnStunningVehicles($value)
 * @method static Tank whereCapturePoints($value)
 * @method static Tank whereDamageDealt($value)
 * @method static Tank whereDamageReceived($value)
 * @method static Tank whereDraws($value)
 * @method static Tank whereDroppedCapturePoints($value)
 * @method static Tank whereFrags($value)
 * @method static Tank whereHits($value)
 * @method static Tank whereHitsPercents($value)
 * @method static Tank whereId($value)
 * @method static Tank whereInGarage($value)
 * @method static Tank whereLosses($value)
 * @method static Tank whereMastery($value)
 * @method static Tank whereMaxFrags($value)
 * @method static Tank whereMaxXp($value)
 * @method static Tank whereShots($value)
 * @method static Tank whereSpotted($value)
 * @method static Tank whereStunAssistedDamage($value)
 * @method static Tank whereStunNumber($value)
 * @method static Tank whereSurvivedBattles($value)
 * @method static Tank whereUpdated($value)
 * @method static Tank whereWargamingId($value)
 * @method static Tank whereWins($value)
 * @method static Tank whereWn8($value)
 * @method static Tank whereWn830($value)
 * @method static Tank whereXp($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TankStat[] $stats
 */
class Tank extends Model
{
    const MASTER_SET = 'all';
    protected $table = 'member_tanks';
    public $timestamps = false;
    /**
     * @var Wn8 null
     */
    private $wn8 = null;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'wargaming_id', 'in_garage', 'wn8', 'wn8_30', 'mastery', 'max_xp', 'max_frags',
        'battles', 'wins', 'losses', 'dropped_capture_points', 'capture_points', 'xp', 'frags',
        'damage_dealt', 'spotted', 'battles_on_stunning_vehicles', 'survived_battles',
        'hits_percents', 'draws', 'damage_received', 'stun_number', 'stun_assisted_damage', 'shots',
        'hits', 'battle_avg_xp'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * @return Member
     */
    public function member()
    {
        return $this->account->member()->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stats()
    {
        return $this->hasMany(TankStat::class, 'wargaming_id', 'wargaming_id');
    }

    /**
     * @return TankStat|null
     */
    public function stats30DaysAgo()
    {
        $last30 = Carbon::today()->addDays(-30);

        /*
         * perfect scenario: the tank has 30 entries, one for each day
         * get max 30 entries from the past where date is bigger then today - 30 days
         * order by date
         */
        $result = $this->stats()
            ->where('account_id', $this->account->id)
            ->whereDate('date', '>=', $last30)
            ->orderByDesc('date')->limit(30)->first();
        if (!count($result)) {
            /*
             * in this case there are no fresh tank stat records for the past month
             * this means the player has not played with this tank for over 30 days
             * just fetch the previous stat behind today
             */
             $result = $this->stats()
                 ->offset(2)->limit(1)->first();
        }
        return $result;
    }
    /**
     * @return Wn8
     */
    protected function wn8()
    {
        if (is_null($this->wn8)) {
            $this->wn8 = App::make(Wn8::class);
        }
        return $this->wn8;
    }

    /**
     * @param Member $member
     * @param $data
     * @return Tank
     */
    public static function createFromWargaming(Member $member, $data)
    {
        $tank = new Tank();
        $tank->wargaming_id = $data['tank_id'];
        $tank->wins = $data['statistics']['wins'];
        $tank->battles = $data['statistics']['battles'];
        $tank->mastery = empty($data['mark_of_mastery']) ?: 'false';

        $tank->account()->associate($member->account()->first());
        $tank->save();

        return $tank;
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

    public function addStatistics(array $data, array $usedExtra)
    {
        $tankData = $this->prepareData($data);
        if ($this->battles < $tankData['battles']) {
            $data['mastery'] = $tankData['mastery'];
            TankStat::updateStats($this, $data, $usedExtra);

            $this->update($tankData);

            return true;
        }
        return false;
    }
    public static function updateStats(Member $member, array $data, $extra)
    {
        $tankId = $data['tank_id'];

        if (empty($tankId)) {
            // @todo report empty tank id
            throw new \Exception('Called tank->updateStats with no "tank_id"');
        }
        $tank = Tank::where('wargaming_id', $tankId)
            ->whereAccountId($member->account->id)
            ->first();

        if (is_null($tank)) {
            // @todo report missing tanks (rentals usually)
            return false;
        }

        return $tank->addStatistics($data, $extra);
    }

    /**
     * Prepare data array to update the tank record
     * @param $data
     * @return array
     */
    private function prepareData($data)
    {
        $result = [];

        if (isset($data[self::MASTER_SET])) {
            $result = $data[self::MASTER_SET];
        }
        $result['mastery'] = $data['mark_of_mastery'] ?: 'false';
        $result['max_xp'] = $data['max_xp'];
        $result['max_frags'] = $data['max_frags'];

        if (isset($data['in_garage'])) {
            $result['in_garage'] = $data['in_garage'];
        }
        // calculate WN8
        $result['wn8'] = $this->wn8()->tank(
            $data['tank_id'],
            $result['damage_dealt'],
            $result['spotted'],
            $result['frags'],
            $result['dropped_capture_points'],
            $result['wins'],
            $result['battles']
        );

        return $result;
    }
    public function currentWn8Stat()
    {
        return [
            'damage_dealt' => $this->damage_dealt,
            'spotted' => $this->spotted,
            'frags' => $this->frags,
            'dropped_capture_points' => $this->dropped_capture_points,
            'wins' => $this->wins,
            'battles' => $this->battles,
        ];
    }
}
