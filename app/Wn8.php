<?php
namespace App;
use Isteam\Wargaming\Api;

/**
 * This file is part of the isteam project.
 *
 * Date: 12/01/18 06:50
 * @author ionut
 */
class Wn8
{
    /**
     * Holds expected tank data (/storage/wn8exp.php)
     * @var array
     */
    protected $baseData;
    /**
     * Rounding precision
     * @var int
     */
    private $precision = 3;
    /**
     * Rounding strategy
     * @var int
     */
    private $rounding = PHP_ROUND_HALF_UP;
    /**
     * Reset structure
     * @var array
     */
    private $reset = [
        'damage' => 0,
        'spot' => 0,
        'frag' => 0,
        'def' => 0,
        'win' => 0,
    ];
    /**
     * Total number of battles (used in calculating a player's WN8)
     * @var int
     */
    private $nrBattles = 0;
    /**
     * Total number of tanks used in calculating a player's WN8
     * @var int
     */
    private $nrTanks = 0;
    /**
     * Holds all the WN8 component sums
     * @var array
     */
    private $sum = [];
    /**
     * Holds all the WN8 expected tank component sums
     * @var array
     */
    private $expected = [];

    /**
     * Wn8 constructor.
     */
    public function __construct()
    {
        $this->sum = $this->reset;
        $this->expected = $this->reset;
    }

    /**
     * Set the expected tank values array
     * @param array $baseData Content of /storage/wn8exp.php
     */
    public function setBaseData(array $baseData)
    {
        $this->baseData = $baseData['data'];
    }

    /**
     * WN8 formula
     * Params structure
     * $avg = [
     *      'damage'  => 0, // average damage
     *      'spot'    => 0, // average spotted tanks
     *      'frag'    => 0, // average kills
     *      'def'     => 0, // average base reset points
     *      'win'     => 0 //
     * ]
     * $exp = [
     *      'damage'  => 0,
     *      'spot'    => 0,
     *      'frag'    => 0,
     *      'def'     => 0,
     *      'win'     => 0
     * ]
     * @param array $avg
     * @param array $exp
     * @return float
     */
    public function formula($avg, $exp)
    {
        $precision = $this->precision;
        $rVals = [];
        foreach (['damage', 'spot', 'frag', 'def', 'win'] as $name) {
            $rVals[$name] = $exp[$name] ?
                round($avg[$name], $precision, $this->rounding) /
                round($exp[$name], $precision, $this->rounding)
            : 0;
        }
        $damage = max(
            0,
            ($rVals['damage'] - 0.22) / (1 - 0.22)
        );
        $win    = max(
            0,
            ($rVals['win'] - 0.71) / (1 - 0.71)
        );
        $frag   = max(
            0,
            min($damage + 0.2, ($rVals['frag'] - 0.12) / (1 - 0.12))
        );
        $spot   = max(
            0,
            min($damage + 0.1, ($rVals['spot'] - 0.38) / (1 - 0.38))
        );
        $def   = max(
            0,
            min($damage + 0.1, ($rVals['def'] - 0.1) / (1 - 0.1))
        );
        $precision = $this->precision;
        $result = 980 * $damage
            +  210 * $damage  * $frag
            +  155 * $frag    * $spot
            +   75 * $def     * $frag
            +  145 * min(1.8, $win);
        return round($result, $precision, $this->rounding);
    }
    public function addTankData($tankId, $damageDealt, $spotted, $frag, $resetPoints, $wins, $battles)
    {
        if (isset($this->baseData[$tankId])) {
            $this->nrBattles += $battles;
            $this->nrTanks++;

            $expected = $this->baseData[$tankId];

            $this->sum['damage'] += $damageDealt;
            $this->sum['spot'] += $spotted;
            $this->sum['frag'] += $frag;
            $this->sum['def'] += $resetPoints;
            $this->sum['win'] += 100 * round($wins / $battles, $this->precision, $this->rounding);

            $this->expected['damage'] += $expected['damage'] * $battles;
            $this->expected['spot'] += $expected['spot'] * $battles;
            $this->expected['frag'] += $expected['frag'] * $battles;
            $this->expected['def'] += $expected['def'] * $battles;
            $this->expected['win'] += $expected['win'];
        }
    }
    public function player()
    {
        // calculate average win-rate for all tanks
        $this->sum['win'] /= $this->nrTanks;
        $this->expected['win'] /= $this->nrTanks;

        $result = $this->formula([
            'damage' => $this->sum['damage'],
            'spot'   => $this->sum['spot'],
            'frag'   => $this->sum['frag'],
            'def'    => $this->sum['def'],
            'win'    => $this->sum['win'],
        ], [
            'damage' => $this->expected['damage'],
            'spot'   => $this->expected['spot'],
            'frag'   => $this->expected['frag'],
            'def'    => $this->expected['def'],
            'win'    => $this->expected['win'],
        ]);

        $this->reset();

        return round($result, $this->precision, $this->rounding);
    }
    public function tank($tankId, $damageDealt, $spotted, $frag, $resetPoints, $wins, $battles)
    {
        // seems AMX Liberte is not in base stats :: 62017
        if (!isset($this->baseData[$tankId])) {
            return false;
        }

        $result = $this->formula([
            'damage' => $battles ? $damageDealt / $battles : 0,
            'spot'   => $battles ? $spotted / $battles     : 0,
            'frag'   => $battles ? $frag / $battles        : 0,
            'def'    => $battles ? $resetPoints / $battles : 0,
            'win'    => $battles ? ($wins / $battles) * 100: 0,
        ], $this->baseData[$tankId]);

        return round($result, $this->precision, $this->rounding);
    }
    private function reset()
    {
        $this->sum = $this->reset;
        $this->expected = $this->reset;
        $this->nrBattles = 0;
        $this->nrTanks = 0;
    }
}