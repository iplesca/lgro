<?php

namespace App\Http\Controllers;

use App\Clan;
use App\Competition;
use App\Member;
use App\User;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Isteam\Wargaming\Api;

class Page extends Controller
{
    public function index(Request $request)
    {
        $this->refreshWgCsrf();
        return $this->useView('landing');
    }
    public function profile(Request $request)
    {
        $user = Auth::user();
        $stats = json_decode($user->stats()->first()->json, true);

        $stats['all']['_type']  = 'all';
        $stats['clan']['_type'] = 'clan';
        $stats['stronghold_skirmish']['_type'] = 'deta';
        $stats['team']['_type'] = 'team';
        $loop = [
            $stats['all'], $stats['clan'], $stats['team'], $stats['stronghold_skirmish']
        ];
        return $this->useView('profile', [
            'data' => Auth::user(),
            'stats' => $loop
        ]);
    }
    public function concurs(Request $request)
    {
        return $this->useView('concurs');
    }
    public function concursSave(Request $request, Competition $concurs)
    {
//        print_r($request->all());
//        die;
        $mId = $request->post('mId');
        $slotOne = $request->post('slotOne');
        $slotTwo = $request->post('slotTwo');
        $matchScores = $concurs->_getScores();

        // quick validate
        if (is_numeric($slotOne['home']) &&
            is_numeric($slotTwo['home'])) {

            $matchScores[$mId]['home']['slotOne'] = intval($slotOne['home']);
            $matchScores[$mId]['home']['slotTwo'] = intval($slotTwo['home']);
            $matchScores[$mId]['hasPoints']['home'] = true;
        }
        if (is_numeric($slotOne['away']) &&
            is_numeric($slotTwo['away'])) {

            $matchScores[$mId]['away']['slotOne'] = intval($slotOne['away']);
            $matchScores[$mId]['away']['slotTwo'] = intval($slotTwo['away']);
            $matchScores[$mId]['hasPoints']['away'] = true;
        }
        $concurs->_saveScores($matchScores);

        return redirect('concurs/echipe');
    }
    private function reset(Competition $concurs)
    {
        $gm = $concurs->generateMatches();
        $concurs->_saveScores($gm);
        return redirect('concurs');
    }
    public function concursRezultate()
    {
        return $this->useView('concurs-rezultate');
    }
    public function concursEchipe(Request $request, Competition $concurs)
    {
//        $this->reset($concurs);
        $concurs->generateMatches();
        $data = $concurs->getData();
        $victoryPoints = 3;
        $drawPoints = 1;
        $losePoints = 0;

        foreach ($data['teams'] as $k => $team) {
            $data['teams'][$k]['id'] = $concurs->assignTeamName($k);
        }
        $scores = $concurs->_getScores();
        $showScores = false;
        $scoresGroups = [];
        foreach ($scores as $matchId => $mData) {

            if ($mData['hasPoints']['home']) {
                $showScores = true;
            }
            // is it a group game?
            if (false !== strpos($matchId, ':')) {
                list($gId, $mId) = explode(':', $matchId);

                $t1Id = $mData['slotOne']['id'];
                $t2Id = $mData['slotTwo']['id'];

                if (!isset($scoresGroups[$gId])) {
                    $scoresGroups[$gId] = [];
                }

                $slotOne = $slotTwo = [
                    'victory' => 0,
                    'defeat' => 0,
                    'draw' => 0,
                    'points' => 0
                ];
                if (isset($mData['home'])) {
                    $homeGame = $mData['home'];
                    
                    if (!empty($homeGame['slotOne']) && !empty($homeGame['slotTwo'])) {
                        if ($homeGame['slotOne'] > $homeGame['slotTwo']) {
                            $slotOne['points'] += $victoryPoints;
                            $slotOne['victory'] += 1;
                            $slotTwo['points'] += $losePoints;
                            $slotTwo['defeat'] += 1;
                        }
                        if ($homeGame['slotOne'] < $homeGame['slotTwo']) {
                            $slotOne['points'] += $losePoints;
                            $slotOne['defeat'] += 1;
                            $slotTwo['points'] += $victoryPoints;
                            $slotTwo['victory'] += 1;
                        }
                        if ($homeGame['slotOne'] == $homeGame['slotTwo']) {
                            $slotOne['points'] += $drawPoints;
                            $slotOne['draw'] += 1;
                            $slotTwo['points'] += $drawPoints;
                            $slotTwo['draw'] += 1;
                        }
                    }
                }
                if (isset($mData['away'])) {
                    $awayGame = $mData['away'];
                    
                    if (!empty($awayGame['slotOne']) && !empty($awayGame['slotTwo'])) {
                        if ($awayGame['slotOne'] > $awayGame['slotTwo']) {
                            $slotOne['points'] += $victoryPoints;
                            $slotOne['victory'] += 1;
                            $slotTwo['points'] += $losePoints;
                            $slotTwo['defeat'] += 1;
                        }
                        if ($awayGame['slotOne'] < $awayGame['slotTwo']) {
                            $slotOne['points'] += $losePoints;
                            $slotOne['defeat'] += 1;
                            $slotTwo['points'] += $victoryPoints;
                            $slotTwo['victory'] += 1;
                        }
                        if ($awayGame['slotOne'] == $awayGame['slotTwo']) {
                            $slotOne['points'] += $drawPoints;
                            $slotOne['draw'] += 1;
                            $slotTwo['points'] += $drawPoints;
                            $slotTwo['draw'] += 1;
                        }
                    }
                }

                if (!isset($scoresGroups[$gId][$t1Id])) {
                    $scoresGroups[$gId][$t1Id] = [
                        'victory' => 0,
                        'defeat' => 0,
                        'draw' => 0,
                        'points' => 0
                    ];
                }
                if (!isset($scoresGroups[$gId][$t2Id])) {
                    $scoresGroups[$gId][$t2Id] = [
                        'victory' => 0,
                        'defeat' => 0,
                        'draw' => 0,
                        'points' => 0
                    ];
                }
                $scoresGroups[$gId][$t1Id]['victory'] += $slotOne['victory'];
                $scoresGroups[$gId][$t1Id]['defeat']  += $slotOne['defeat'];
                $scoresGroups[$gId][$t1Id]['draw']    += $slotOne['draw'];
                $scoresGroups[$gId][$t1Id]['points']  += $slotOne['points'];

                $scoresGroups[$gId][$t2Id]['victory'] += $slotTwo['victory'];
                $scoresGroups[$gId][$t2Id]['defeat']  += $slotTwo['defeat'];
                $scoresGroups[$gId][$t2Id]['draw']    += $slotTwo['draw'];
                $scoresGroups[$gId][$t2Id]['points']  += $slotTwo['points'];
            }
        }
        // sort
        foreach ($scoresGroups as $gId => $teams) {
            $sss = $scoresGroups[$gId];
            uasort($sss, function ($a, $b) {
                if ($a['points'] < $b['points']) {
                    return 1;
                } elseif ($a['points'] > $b['points']) {
                    return -1;
                } else {
                    if ($a['victory'] < $b['victory']) {
                        return 1;
                    } elseif ($a['victory'] > $b['victory']) {
                        return -1;
                    } else {
                        if ($a['defeat'] < $b['defeat']) {
                            return -1;
                        } elseif ($a['defeat'] > $b['defeat']) {
                            return 1;
                        } else {
                            return 0;
                        }
                    }
                }
            });
            $scoresGroups[$gId] = $sss;
        }
//        $concurs->_saveScores($concurs->generateMatches());
//        $gg = $concurs->_getScores();


        return $this->useView('concurs-echipe', [
            'data' => $data,
            'showScores' => $showScores,
            'scoresGroup' => $scoresGroups,
            'scores' => $scores,
        ]);
    }
    public function ofiter(Request $request, User $user)
    {
        return $this->useView('ofiter');
//        if (Auth::user()->can('isOfficer')) {
//            return view('ofiter');
//        } else {
//            $request->session()->flash('pop_message', 'Neautorizat');
//            return redirect('');
//        }
    }
    public function test(Request $request, Api $api)
    {
        $params = $api->tanks()->getAllTanks();
        echo "<pre>";
        print_r($params);
        exit;
        return view('stadard.test', $params);
    }

    private function useView($name, $data = array())
    {
        return view(ISTEAM_TEMPLATE . '.' . $name, $data);
    }
}
