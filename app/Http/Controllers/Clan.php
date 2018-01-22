<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Clan as ClanModel;

class Clan extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function members()
    {
        $members = ClanModel::getMembersByWargamingId(CLAN_ID)->members();
        $members = collect($members)->map(function ($item, $key) {
            $item->joined = Carbon::createFromFormat('Y-m-d H:i:s', $item->joined)
                ->diffInDays(Carbon::now());
            $item->last_played = Carbon::createFromFormat('Y-m-d H:i:s', $item->logout)
                ->diffInDays(Carbon::now());
            $item->role = Str::studly($item->role);
        });

        return $this->useView('clan-stats', [
            'members' => $members
        ]);
    }
}

