<?php

namespace App\Http\Controllers;

use App\Clan;
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
        return view('landing');
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
        return view('profile', [
            'data' => Auth::user(),
            'stats' => $loop
        ]);
    }
    public function concurs(Request $request)
    {
        return view('concurs');
    }
    public function concursEchipe(Request $request)
    {
        $teamNames = ['Alfa', 'Bravo', 'Charlie', 'Delta', 'Echo', 'Foxtrot', 'Golf', 'Hotel', 'India', 'Juliet', 'Kilo', 'Lima', 'Mike', 'November', 'Oscar', 'Papa', 'Quebec', 'Romeo', 'Sierra', 'Tango', 'Uniform', 'Victor', 'Whiskey', 'X-ray', 'Yankee', 'Zulu'];

        $data = [
            'teams' => [
                't1' => [
                    'name' => 'Echipa 1',
                    'players' => ['AlecsandruCorhan', 'marioseer', 'broscoi1']
                ],
                't2' => [
                    'name' => 'Echipa 2',
                    'players' => ['SirLucasIV', '_Syu_', 'zaman95']
                ],
                't3' => [
                    'name' => 'Echipa 3',
                    'players' => ['1Alexandrw', 'ligrivis', 'gabycarutasoiu']
                ],
                't4' => [
                    'name' => 'Echipa 4',
                    'players' => ['xxWOLVERINExxx', 'deniyz', 'stefy2014']
                ],
                't5' => [
                    'name' => 'Echipa 5',
                    'players' => ['uslaro', 'Panzerwaffe', 'ciukash']
                ],
                't6' => [
                    'name' => 'Echipa 6',
                    'players' => ['Deputy_Thunder', 'tenebras', 'robert_adrian2013']
                ],
                't7' => [
                    'name' => 'Echipa 7',
                    'players' => ['DemonSMV', 'dmmoisi', 'Sulla_Felix']
                ],
                't8' => [
                    'name' => 'Echipa 8',
                    'players' => ['acid8urn', 'GX5570', 'KinezGL']
                ],
                't9' => [
                    'name' => 'Echipa 9',
                    'players' => ['Blue_Banana', 'aurel19747777', 'zugamihai']
                ],
                't10' => [
                    'name' => 'Echipa 10',
                    'players' => ['Marius6354 ', 'UrSu77', 'iuga_mari78']
                ],
            ],
            'groups' => [
                'g1' => ['t3', 't4', 't7', 't9', 't10'],
                'g2' => ['t1', 't2', 't5', 't6', 't8'],
            ],
            'matches' => [
                'pos' => ['p1' => 0, 'p2' => 1, 'p3' => 2, 'p4' => 3, 'p5' => 4],
                'qualify' => [
                    ['p1', 'p2'], ['p3', 'p4'], ['p1', 'p3'], ['p2', 'p5'], ['p2', 'p4'], ['p1', 'p5'], ['p1', 'p4'],
                    ['p3', 'p5'], ['p2', 'p3'], ['p4', 'p5']
                ]
            ]
        ];

        foreach ($data['teams'] as $k => $team) {
            $teamPos = intval(str_replace('t', '', $k)) - 1;
            $data['teams'][$k]['id'] = $teamNames[$teamPos];
        }

        return view('concurs-echipe', [
            'data' => $data
        ]);
    }
    public function concursRezultate(Request $request)
    {
        return view('concurs-rezultate');
    }
    public function ofiter(Request $request, User $user)
    {
        return view('ofiter');
//        if (Auth::user()->can('isOfficer')) {
//            return view('ofiter');
//        } else {
//            $request->session()->flash('pop_message', 'Neautorizat');
//            return redirect('');
//        }
    }
    public function test(Request $request, Api $api)
    {
        $clans = Clan::all();
        foreach ($clans as $clan) {
            $existingMembers = $clan->members()->get();
            $members = $api->server()->getClanMembers($clan->wargaming_id);

            // check for member that left the clan
            foreach ($existingMembers as $em) {
                // no longer present in the members list
                if (! isset($members[$em->wargaming_id])) {
                    $em->delete('[auto] no reason');
                }
            }
            // check for new members
            foreach ($members as $wargamingId => $m) {
                $member = $existingMembers->firstWhere('wargaming_id', $wargamingId);

                if (! $member) {
                    // re-add, in case it's a returning member
                    Member::readd($m, $clan);
                }
            }
        }



//        $found = $existingMembers->firstWhere('wargaming_id', 519931899)->first()->toArray();
        $params = [];
        $existingMembers = $api->server()->getClanMembers(env('CLAN_ID'));
//        $test = $api->tanks()->getUserData(Auth::user()->wargaming_id;
//        $params['test'] = print_r($test, true);
//        $params['existingMembers'] = print_r($existingMembers, true);
//        $params['members'] = print_r($members, true);
        return view('test', $params);
    }
}
