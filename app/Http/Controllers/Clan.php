<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Clan as ClanModel;

class Clan extends Controller
{
    private $rankSort = [
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function members()
    {
        $rankSort = $this->rankSort;
        $members = ClanModel::getByWargamingId(CLAN_ID)->members()
            ->get()
            ->sort(function ($a, $b) use ($rankSort) {
                if ($rankSort[$a->role] == $rankSort[$b->role]) {
                    return strcasecmp($a->nickname, $b->nickname);
                }
                return $rankSort[$a->role] < $rankSort[$b->role] ? -1 : 1;
            });

        for ($i=0; $i < count($members); $i++) {
            $members[$i]->joined = Carbon::createFromFormat('Y-m-d H:i:s', $members[$i]->joined)
                ->diffInDays(Carbon::now());
            $members[$i]->last_played = Carbon::createFromFormat('Y-m-d H:i:s', $members[$i]->logout)
                ->diffInDays(Carbon::now());
            $members[$i]->role = Str::studly($members[$i]->role);

            $members[$i]->wn8Level = $this->getWn8Level($members[$i]->wn8);
            $members[$i]->wn830Level = $this->getWn8Level($members[$i]->wn8_30);
        }

        return $this->useView('clan-stats', [
            'members' => $members
        ]);
    }
    private function getWn8Level($value)
    {
        $result = 'superunicum';
        if ($value < 2900) {
            $result = 'unicum';
        }
        if ($value < 2500) {
            $result = 'great';
        }
        if ($value < 2000) {
            $result = 'verygood';
        }
        if ($value < 1600) {
            $result = 'good';
        }
        if ($value < 1200) {
            $result = 'aboveaverage';
        }
        if ($value < 900) {
            $result = 'average';
        }
        if ($value < 650) {
            $result = 'belowaverage';
        }
        if ($value < 450) {
            $result = 'bad';
        }
        if ($value < 300) {
            $result = 'verybad';
        }
        return $result;
    }
}
