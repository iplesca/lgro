<?php
namespace App\Administration;

/**
 * This file is part of the isteam project.
 *
 * Date: 07/01/18 23:06
 * @author ionut
 */
use Illuminate\Support\Facades\Log;
use App\Clan;
use App\Member;
use Isteam\Wargaming\Api;

class ClanActions
{
    /**
     * Updates clan meta data changes
     * @param Api $api
     */
    public function updateData(Api $api)
    {
        Log::info('[cron][update clan data] running');

        $clans = Clan::all();
        foreach ($clans as $clan) {
            $clanData = $api->server()->getClanInfo($clan->wargaming_id);
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
    }

    /**
     * Checks for member changes in a clan
     * @param Api $api
     */
    public function checkMembers(Api $api)
    {
        Log::info('[cron][check clan members] running');

        $clans = Clan::all();
        foreach ($clans as $clan) {
            $leftClan = 0;
            $newMember = 0;
            $existingMembers = $clan->members()->get();
            $members = $api->server()->getClanMembers($clan->wargaming_id);
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
                    // re-add, in case it's a returning member
                    Member::readd($m, $clan);
                }
            }
            Log::info('[cron][check clan members] Clan <' . $clan->name. '> -- Existing: ' . count($existingMembers) . '. Query: ' . count($members) . ' Left: ' .$leftClan . '. New: ' . $newMember);
        }
    }
}