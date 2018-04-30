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

        return $this->useView('profile', [
            'user' => $user,
            'member' => $memberStats
        ]);
    }
}
