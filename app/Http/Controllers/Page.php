<?php
namespace App\Http\Controllers;

use App\Administration\ClanActions;
use App\Administration\PlayerActions;
use App\Models\Clan;
use App\Competition;
use App\Models\Member;
use App\Models\User;
use App\Wn8;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Isteam\Wargaming\Api;

class Page extends Controller
{
    public function index()
    {
        $this->refreshWgCsrf();
        return $this->useView('landing');
    }
    public function profileStandard()
    {

    }
    public function clanStats()
    {
        $roleSort = [
            'commander' => 1,
            'executive_officer' => 2,
            'personnel_officer' => 3,
            'quartermaster' => 4,
            'intelligence_officer' => 5,
            'combat_officer' => 6,
            'recruitment_officer' => 7,
            'junior_officer' => 8,
            'private' => 9,
            'recruit' => 10,
            'reservist' => 11,
        ];
        $members = Clan::getByWargamingId(CLAN_ID)->members()
            ->get();
        $members = collect($members)->sort(function ($a, $b) use ($roleSort) {
            if ($roleSort[$a->role] == $roleSort[$b->role]) {
                return strcasecmp($a->nickname, $b->nickname);
            }
            return $roleSort[$a->role] < $roleSort[$b->role] ? -1 : 1;
        })->map(function ($item, $key) {
            $item->joined = Carbon::createFromFormat('Y-m-d H:i:s', $item->joined)->diffInDays(Carbon::now());
            return $item;
        })->map(function ($item, $key) {
            $item->last_played = Carbon::createFromFormat('Y-m-d H:i:s', $item->logout)->diffInDays(Carbon::now());
            return $item;
        })->map(function ($item, $key) {
            $item->role = Str::studly($item->role);
            return $item;
        });
        return $this->useView('clan-stats', [
            'members' => $members
        ]);
    }
    public function profile()
    {
        $user = Auth::user();
        $memberStats = $user->membership;
//        $memberStats = json_decode($user->membership()->first()->stats, true);

        return $this->useView('profile', [
            'user' => $user,
            'member' => $memberStats
        ]);
    }
    public function concurs()
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
    public function test(Request $request, Api $api, Wn8 $wn8)
    {
//        $member = Member::find(1);
//        $tankId = 5137; // tiger2
//        $tank = $member->tanks()->where('wargaming_id', $tankId)->first();
//        $a = 1;
//        exit;
//        $act = new PlayerActions();
//        $act->updateWn8(Member::find(34));
        $act = new ClanActions();
        $act->updateMemberWn8(Clan::find(1));
//        $act->checkMembers(Clan::find(1));

        return;
    }
    public function test2(Request $request, Api $api, Wn8 $wn8)
    {
        $playerId = 514353122; // fury
        $playerId = 519931899; // lucas
        $token = 'f8502f3642b90e33ae7cbdcf427a9b9f310a641b'; // lucas
        $token = ''; // empty
        $tankId = 5377; // is3
        $tankId = 5137; // tiger2
        $tankId = 0;
        /*
        $params = $api->tanks()->getPlayerTankStats($playerId, $token, $tankId);

//        $params = File::getRequire(Storage::path('lucas.php'));
        $wn8Player = 0;
        foreach ($params as $tankData) {
            $tankId = $tankData['tank_id'];
            $data = $tankData['all'];

            $wn8->addTankData(
                $tankId,
                $data['damage_dealt'],
                $data['spotted'],
                $data['frags'],
                $data['dropped_capture_points'],
                $data['wins'],
                $data['battles']
            );
        }
        $wn8Player = $wn8->player();
        echo "WN8 IS-3 = " . $wn8Player . "<br>";
        */
//        echo "<pre>";
//        print_r($params);
//        $params = $api->tanks()->getPlayerTankAchievements($playerId, $token, $tankId);
//        echo "<pre>";
//        print_r($params);
        $params = $api->tanks()->getPlayerTankStats($playerId, $token, $tankId);
        echo "<pre>";
//        $allKeys = array_merge($params[0]['clan'], $params[0]['stronghold_skirmish'], $params[0]['stronghold_skirmish'], $params[0]['regular_team'], $params[0]['regular_team'], $params[0]['company'], $params[0]['random'], $params[0]['company'], $params[0]['all'], $params[0]['company'], $params[0]['stronghold_defense'], $params[0]['team'], $params[0]['globalmap']);
//        echo "common <br>";
//        print_r(array_intersect_key($allKeys, $params[0]['clan'], $params[0]['stronghold_skirmish'], $params[0]['stronghold_skirmish'], $params[0]['regular_team'], $params[0]['regular_team'], $params[0]['company'], $params[0]['random'], $params[0]['company'], $params[0]['all'], $params[0]['company'], $params[0]['stronghold_defense'], $params[0]['team'], $params[0]['globalmap']));
//        echo "only clan<br>";
//        print_r(array_diff_key($params[0]['clan'], $params[0]['stronghold_skirmish'], $params[0]['stronghold_skirmish'], $params[0]['regular_team'], $params[0]['regular_team'], $params[0]['company'], $params[0]['random'], $params[0]['company'], $params[0]['all'], $params[0]['company'], $params[0]['stronghold_defense'], $params[0]['team'], $params[0]['globalmap']));
//        echo "only all<br>";
//        print_r(array_diff_key($params[0]['all'], $params[0]['stronghold_skirmish'], $params[0]['stronghold_skirmish'], $params[0]['regular_team'], $params[0]['regular_team'], $params[0]['company'], $params[0]['random'], $params[0]['company'], $params[0]['clan'], $params[0]['company'], $params[0]['stronghold_defense'], $params[0]['team'], $params[0]['globalmap']));
//        echo "only all<br>";
//        print_r(array_diff_key($params[0]['random'], $params[0]['stronghold_skirmish'], $params[0]['stronghold_skirmish'], $params[0]['regular_team'], $params[0]['regular_team'], $params[0]['company'], $params[0]['all'], $params[0]['company'], $params[0]['clan'], $params[0]['company'], $params[0]['stronghold_defense'], $params[0]['team'], $params[0]['globalmap']));

        print_r($params);

//        $params = $api->tanks()->getPlayerTanks($playerId, $token);
//        echo "<pre>";
//        print_r($params);
        exit;
        return view('stadard.test', $params);
    }
}
