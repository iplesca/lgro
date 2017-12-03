<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
