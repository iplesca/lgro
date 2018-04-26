<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Clan as ClanModel;

class Clan extends Controller
{
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
}

