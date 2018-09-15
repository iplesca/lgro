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
    const DEFAULT_SET = '_default_';
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
    private $set = [
        self::DEFAULT_SET => [
            /**
             * Holds all the WN8 component sums
             * @var array
             */
            'sum' => [],
            /**
             * Holds all the WN8 expected tank component sums
             * @var array
             */
            'expected' => [],
            /**
             * Total number of battles (used in calculating a player's WN8)
             * @var int
             */
            'nrBattles' => 0,
            /**
             * Total number of tanks used in calculating a player's WN8
             * @var int
             */
            'nrTanks' => 0
        ]
    ];

    /**
     * Wn8 constructor.
     */
    public function __construct()
    {
        $this->resetAll();
    }

    /**
     * Set the expected tank values array
     * @param array $baseData Content of /storage/wn8exp.php
     */
    public function setBaseData(array $baseData)
    {
        $this->baseData = $baseData['data'];
    }
    public function createSet($name)
    {
        $this->reset($name);
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
    public function addTankData(
        $tankId,
        $damageDealt,
        $spotted,
        $frag,
        $resetPoints,
        $wins,
        $battles,
        $set = self::DEFAULT_SET
    ) {
        if (isset($this->baseData[$tankId])) {
            $this->set[$set]['nrBattles'] += $battles;
            $this->set[$set]['nrTanks']++;

            $expected = $this->baseData[$tankId];

            $this->set[$set]['sum']['damage'] += $damageDealt;
            $this->set[$set]['sum']['spot'] += $spotted;
            $this->set[$set]['sum']['frag'] += $frag;
            $this->set[$set]['sum']['def'] += $resetPoints;
            $this->set[$set]['sum']['win'] += 100 * round($wins / $battles, $this->precision, $this->rounding);

            $this->set[$set]['expected']['damage'] += $expected['damage'] * $battles;
            $this->set[$set]['expected']['spot'] += $expected['spot'] * $battles;
            $this->set[$set]['expected']['frag'] += $expected['frag'] * $battles;
            $this->set[$set]['expected']['def'] += $expected['def'] * $battles;
            $this->set[$set]['expected']['win'] += $expected['win'];
        }
    }
    public function addTankData2($tankId, $damageDealt, $spotted, $frag, $resetPoints, $wins, $battles)
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
    public function player($set = self::DEFAULT_SET)
    {
        if (0 == $this->set[$set]['nrTanks']) {
            return 0;
        }
        // calculate average win-rate for all tanks
        $this->set[$set]['sum']['win'] /= $this->set[$set]['nrTanks'];
        $this->set[$set]['expected']['win'] /= $this->set[$set]['nrTanks'];

        $result = $this->formula([
            'damage' => $this->set[$set]['sum']['damage'],
            'spot'   => $this->set[$set]['sum']['spot'],
            'frag'   => $this->set[$set]['sum']['frag'],
            'def'    => $this->set[$set]['sum']['def'],
            'win'    => $this->set[$set]['sum']['win'],
        ], [
            'damage' => $this->set[$set]['expected']['damage'],
            'spot'   => $this->set[$set]['expected']['spot'],
            'frag'   => $this->set[$set]['expected']['frag'],
            'def'    => $this->set[$set]['expected']['def'],
            'win'    => $this->set[$set]['expected']['win'],
        ]);

        $this->reset($set);

        return round($result, $this->precision, $this->rounding);
    }
    public function tankAndPlayer(
        $tankId,
        $damageDealt,
        $spotted,
        $frag,
        $resetPoints,
        $wins,
        $battles,
        $set = self::DEFAULT_SET
    ) {
        $this->addTankData($tankId, $damageDealt, $spotted, $frag, $resetPoints, $wins, $battles, $set);
        return $this->tank($tankId, $damageDealt, $spotted, $frag, $resetPoints, $wins, $battles);
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
    public function resetAll()
    {
        foreach ($this->set as $key => $value) {
            $this->reset($key);
        }
    }
    private function reset($set)
    {
        $this->set[$set]['sum'] = $this->reset;
        $this->set[$set]['expected'] = $this->reset;
        $this->set[$set]['nrBattles'] = 0;
        $this->set[$set]['nrTanks'] = 0;
    }
}