<?php
namespace App\Http\Controllers;

use App\Administration\ClanActions;
use Carbon\Carbon;
use App\Models\Clan as ClanModel;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return $this->useView('dashboard-index');
    }
    public function statistics()
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

