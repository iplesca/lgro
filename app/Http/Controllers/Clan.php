<?php
namespace App\Http\Controllers;

use App\Administration\ClanActions;
use Carbon\Carbon;
use App\Models\Clan as ClanModel;
use Illuminate\Support\Facades\Auth;

class Clan extends Controller
{
    public function info()
    {
        $this->refreshWgCsrf();
        return $this->useView('landing');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function members()
    {
        $members = ClanModel::getMembersByWargamingId(CLAN_ID);
        $members = collect($members)->map(function ($item) {
            $set = [];
            $set['role'] = $item->role;
            $set['nickname'] = $item->nickname;
            $set['battles'] = $item->stats['battles'];
            $set['score'] = $item->score;
            $set['wn8'] = $item->wn8;
            $set['wn8_30'] = $item->wn8_30;
            $set['joined'] = Carbon::createFromFormat('Y-m-d H:i:s', $item->joined)
                ->diffInDays(Carbon::now());
            if ($item->online) {
                $set['logout'] = false;
            } else {
                $set['logout'] = now();
                if (!is_null($item->logout)) {
                    $set['logout'] = Carbon::createFromFormat('Y-m-d H:i:s', $item->logout)->startOfDay()
                        ->diffInDays(Carbon::today()->startOfDay());
                }
            }
            $set['id'] = $item->id;
            $set['wn8color'] = wn8color($item->wn8);
            $set['wn830color'] = wn8color($item->wn8_30);

            return $set;
        });

        return $this->useView('clan-stats', [
            'members' => $members
        ]);
    }
    public function dashboard()
    {
        // 3 => 2
        // 1 => M
        // 2 => 1
        // false => 3
        // false => false
        $clanOps = new ClanActions();

        $top15HeavyLastDay = $clanOps->topHeavy(Auth::user()->membership->clan_id, 1)->limit(1)->get();
        $top15HeavyLast7Days = $clanOps->topHeavy(Auth::user()->membership->clan_id, 7)->limit(15)->get();
        $top15HeavyLast30Days = $clanOps->topHeavy(Auth::user()->membership->clan_id, 7)->limit(30)->get();

        return $this->useView('dashboard', [
            'ht1' => $top15HeavyLastDay,
            'ht7' => $top15HeavyLast7Days,
            'ht30' => $top15HeavyLast30Days
        ]);
    }
}

