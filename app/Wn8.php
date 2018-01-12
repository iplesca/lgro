<?php
namespace App;

/**
 * This file is part of the isteam project.
 *
 * Date: 12/01/18 06:50
 * @author ionut
 */
class Wn8
{
    protected $baseData;
    protected $userData;
    protected $nrTanks;

    public function __construct($baseData, $userData)
    {
        $this->baseData = $baseData;
        $this->userData = $userData;
    }
    public function calculate()
    {
        $rValues = $this->generateRValues2();

        $rWinC1 = $this->getCVal($rValues['v1']['rWIN'], 0.71);
        $rWinC2 = $this->getCVal($rValues['v2']['rWIN'], 0.71);

        $rDmgC1 = $this->getCVal($rValues['v1']['rDMG'], 0.22);
        $rDmgC2 = $this->getCVal($rValues['v2']['rDMG'], 0.22);

        $rFragC1 = $this->getCVal($rValues['v1']['rFRAG'], 0.12, $rDmgC1 + 0.2);
        $rFragC2 = $this->getCVal($rValues['v2']['rFRAG'], 0.12, $rDmgC2 + 0.2);

        $rSpotC1 = $this->getCVal($rValues['v1']['rSPOT'], 0.38, $rDmgC1 + 0.1);
        $rSpotC2 = $this->getCVal($rValues['v2']['rSPOT'], 0.38, $rDmgC2 + 0.1);

        $rDefC1 = $this->getCVal($rValues['v1']['rDEF'], 0.1, $rDmgC1 + 0.1);
        $rDefC2 = $this->getCVal($rValues['v2']['rDEF'], 0.1, $rDmgC2 + 0.1);

        $wn8v1 = 980*$rDmgC1 + 210*$rDmgC1*$rFragC1 + 155*$rFragC1*$rSpotC1 + 75*$rDefC1*$rFragC1 + 145*MIN(1.8,$rWinC1);
        $wn8v2 = 980*$rDmgC2 + 210*$rDmgC2*$rFragC2 + 155*$rFragC2*$rSpotC2 + 75*$rDefC2*$rFragC2 + 145*MIN(1.8,$rWinC2);

        return [
            'v1' => $wn8v1,
            'v2' => $wn8v2,
        ];
    }
    private function generateCValues($rv)
    {
        $result['damage'] = $this->getCVal($rv['damage'], 0.22);
        $result['win']    = $this->getCVal($rv['win'],    0.71);

        $result['frag']   = $this->getCVal($rv['frag'],  0.12, $result['damage'] + 0.2);
        $result['spot']   = $this->getCVal($rv['spot'],  0.38, $result['damage'] + 0.1);
        $result['def']    = $this->getCVal($rv['def'],   0.1,  $result['damage'] + 0.1);

        return $result;
    }
    private function getCVal($rVal, $const, $minVal = null)
    {
        $val = ($rVal - $const) / (1 - $const);

        if (!is_null($minVal)) {
            $val = min($minVal, $val);
        }

        return max(0, $val);
    }
    private function formula($dmg, $spot, $frag, $def, $win)
    {
        return 980 * $dmg
            +  210 * $dmg  * $frag
            +  155 * $frag * $spot
            +   75 * $def  * $frag
            +  145 * min(1.8, $win);
    }
    private function generateAverages($tankData)
    {
        $result = [
            'damage' => 0,
            'spot' => 0,
            'frags' => 0,
            'def' => 0,
            'win' => 0,
        ];

        if (0 !== $tankData['battles']) {
            $result = [
                'damage' => $tankData['damage_dealt'] / $tankData['battles'],
                'spot'   => $tankData['spotted'] / $tankData['battles'],
                'frags'  => $tankData['frags'] / $tankData['battles'],
                'def'    => $tankData['dropped_capture_points'] / $tankData['battles'],
                'win'    => $tankData['wins'] / $tankData['battles'],
            ];
        }
        return $result;
    }
    private function generateRValues($avg, $exp)
    {
        $result = [];
        $entries = ['damage', 'spot', 'frag', 'def', 'win'];
        foreach ($entries as $type) {
            $result[$type] = (0 !== $exp[$type]) ? $avg[$type] / $exp[$type] : 0;
        }
        return $result;
    }
    public function generateRValues2()
    {
        // generate ratios
        $totals = [
            'damage' => ['avg' => 0, 'exp' => 0, 'ratio' => 0],
            'spot' => ['avg' => 0, 'exp' => 0, 'ratio' => 0],
            'frag' => ['avg' => 0, 'exp' => 0, 'ratio' => 0],
            'def' => ['avg' => 0, 'exp' => 0, 'ratio' => 0],
            'win' => ['avg' => 0, 'exp' => 0, 'ratio' => 0],
        ];
        $this->nrTanks = 0;
        foreach ($this->userData as $tank) {
            $tankId = $tank['tank_id'];
            $data = $tank['all'];

            // seems AMX Liberte is not in base stats :: 62017
            if (isset($this->baseData[$tankId])) {

                $this->nrTanks++;
                $base = $this->baseData[$tankId];

                $averages = $this->generateAverages($tank['all']);
                $rValues = $this->generateRValues($averages, [
                    'damage' => $base['expDamage'],
                    'spot'   => $base['expSpot'],
                    'frag'   => $base['expFrag'],
                    'def'    => $base['expDef'],
                    'win'    => $base['expWinRate'],
                ]);
            }
        }
    }
}