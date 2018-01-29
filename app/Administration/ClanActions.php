<?php
namespace App\Administration;

/**
 * This file is part of the isteam project.
 *
 * Date: 07/01/18 23:06
 * @author ionut
 */
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Clan;
use App\Models\Member;

class ClanActions extends Base
{
    /**
     * Updates all clans meta data changes
     */
    public function updateAllMetaData()
    {
        Log::info('[cron][update ALL clan meta data] running');

        $clans = Clan::all();
        foreach ($clans as $clan) {
            $this->updateMetaData($clan);
        }
    }

    /**
     * Update the meta data for a clan
     * @param Clan $clan
     */
    public function updateMetaData(Clan $clan)
    {
        $clanData = $this->api->server()->getClanInfo($clan->wargaming_id);
        if (!is_null($clanData)) {
            $clan->tag = $clanData['tag'];
            $clan->description = $clanData['description_html'];
            $clan->motto = $clanData['motto'];
            $clan->color = $clanData['color'];
            $clan->emblem32 = $clanData['emblems']['x32']['portal'];
            $clan->emblem64 = $clanData['emblems']['x64']['portal'];
            $clan->emblem195 = $clanData['emblems']['x195']['portal'];

            if (0 < count($clan->getDirty())) {
                $msg = 'Updated fields: ' . implode(',', $clan->getDirty());
            } else {
                $msg = 'n/a';
            }

            Log::info('[cron][update clan data] Clan <' . $clan->name . '> -- ' . $msg);
            $clan->save();
        }
    }

    /**
     * Checks/updates member changes for all clans
     */
    public function checkMembersAllClans()
    {
        Log::info('[cron][check ALL clan members] started');

        $clans = Clan::all();
        foreach ($clans as $clan) {
            $this->checkMembers($clan);
        }
        Log::info('[cron][check ALL clan members] finished');
    }
    /**
     * Checks/updates for member changes in a clan
     * @param Clan $clan
     */
    public function checkMembers(Clan $clan)
    {
        $playerActions = new PlayerActions();

        $leftClan = 0;
        $newMember = 0;
        $existingMembers = $clan->members()->with('account')->get();
        $members = $this->api->server()->getClanMembers($clan->wargaming_id);
        // check for member that left the clan
        foreach ($existingMembers as $em) {
            // no longer present in the members list
            if (! isset($members[$em->wargaming_id])) {
                $leftClan++;
                $em->delete('[auto] no reason');
            }
        }
        // check for new members
        foreach ($members as $wargamingId => $m) {
            $member = $existingMembers->firstWhere('wargaming_id', $wargamingId);

            if (! $member) {
                $newMember++;
                $playerActions->reinstateMember($m, $clan);
            }
        }
        Log::info('[cron][check clan members] Clan <' . $clan->name. '> -- Existing: '.
            count($existingMembers) . '. Query: ' . count($members) . ' Left: '.
            $leftClan . '. New: ' . $newMember);
    }
    public function updateAllTankStats()
    {
        Log::info('[cron][update ALL tank stats] running');

        $clans = Clan::all();
        foreach ($clans as $clan) {
            $this->updateTankStats($clan);
        }
        Log::info('[cron][update ALL tank stats] finished');
    }
    public function updateTankStats(Clan $clan)
    {
        $yesterday = Carbon::yesterday();

        // Must enlarge the member-pool to all
        // filter our member not logged in today
        // and order by desc by last updated (in case some members were skipped)
        $members = $clan->members()->with('user')
            ->whereDate('logout', '>=', $yesterday)
            ->orderByDesc('updated_at')
            ->get();
        $playerActions = new PlayerActions();

        $updated = 0;
        foreach ($members as $member) {
            $updated += $playerActions->updateTankStats($member) ? 1 : 0;
        }
        Log::info('[cron][update tank stats] Clan '. $clan->name . '. Updated: ' . $updated);
    }
    /**
     * #Job-handle
     * Updates all member statistics for all clans
     */
    public function updateAllMemberStatsData()
    {
        Log::info('[cron][private data] running');

        $clans = Clan::all();
        foreach ($clans as $clan) {
            $this->updateMemberStatsData($clan);
        }
    }
    /**
     * Updates statistics for all members of a clan
     * @param Clan $clan
     */
    public function updateMemberStatsData(Clan $clan)
    {
        $updated = 0;
        $playerActions = new PlayerActions();
//        $yesterday = Carbon::yesterday();

        // Must enlarge the member-pool to all
        // filter our member not logged in today
        // and order by desc by last updated (in case some members were skipped)
        $members = $clan->members()->with('user')
//            ->whereDate('logout', '>=', $yesterday)
            ->orderByDesc('updated_at')
            ->get();

        foreach ($members as $member) {
            $updated += $playerActions->updateStats($member) ? 1 : 0;
        }
        Log::info('[cron][private data] Clan: '.$clan->name . '. Updated: ' . $updated);
    }
    public function updateAllWn8()
    {
        Log::info('[cron][update ALL wn8] running');

        $clans = Clan::all();
        foreach ($clans as $clan) {
            $this->updateMemberWn8($clan);
        }

        Log::info('[cron][update ALL wn8] finished');
    }
    /**
     * Updates WN8 values for all members of a clan
     * @param Clan $clan
     */
    public function updateMemberWn8(Clan $clan)
    {
        $updated = 0;
        $playerActions = new PlayerActions();

//        $yesterday = Carbon::yesterday();

        // filter our member not logged in today
        // and order by desc by last updated (in case some members were skipped)
        $members = $clan->members()->with('user')
//            ->whereDate('logout', '>=', $yesterday)
            ->orderByDesc('updated_at')
            ->get();

        foreach ($members as $member) {
            $updated += $playerActions->updateWn8($member) ? 1 : 0;
        }
        Log::info('[cron][update wn8] Clan: '.$clan->name . '. Updated: ' . $updated);
    }
    ////

    public function searchAllNewTanks()
    {
        Log::info('[cron][search ALL new tank] running');

        $clans = Clan::all();
        foreach ($clans as $clan) {
            $this->searchNewTanks($clan);
        }
        Log::info('[cron][search ALL new tank] finished');
    }
    public function searchNewTanks(Clan $clan)
    {
        $members = $clan->members()->get();
        $playerActions = new PlayerActions();

        $updated = 0;
        foreach ($members as $member) {
            $updated += $playerActions->updateNewTanks($member) ? 1 : 0;
        }
        Log::info('[cron][new tanks] Clan '. $clan->name . '. Updated: ' . $updated);
    }
    public function getOnlineMembers($clanWargamingId)
    {
        return $this->api->server()->getClanOnlineMembers($clanWargamingId);
    }
}
