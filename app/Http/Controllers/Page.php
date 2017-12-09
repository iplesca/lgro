<?php

namespace App\Http\Controllers;

use App\Clan;
use App\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function test(Request $request, Api $api)
    {
//        $found = $existingMembers->firstWhere('wargaming_id', 519931899)->first()->toArray();
        $test = [];
//        $test = $api->server()->getClanMembers(env('CLAN_ID'));
//        $test = $api->tanks()->getUserData(Auth::user()->wargaming_id;
        $params['test'] = print_r($test, true);
        $params['existingMembers'] = print_r($existingMembers, true);
        $params['members'] = print_r($members, true);
        return view('test', $params);
    }
}
