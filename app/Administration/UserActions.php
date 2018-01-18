<?php
namespace App\Administration;

/**
 * This file is part of the isteam project.
 *
 * Date: 07/01/18 23:06
 * @author ionut
 */
use Illuminate\Support\Facades\Log;
use App\Models\Clan;
use App\Models\Member;
use Isteam\Wargaming\Api;

class UserActions
{
    /**
     * Refresh access token
     * @param Api $api
     */
    public function checkTokens(Api $api)
    {
        Log::info('[cron][check tokens] running');

        $clans = Clan::all();
        foreach ($clans as $clan) {
            $users = Member::has('user')->with('user')->where('clan_id', $clan->id);
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
                    // @todo get private data
                    // re-add, in case it's a returning member
                    Member::readd($m, $clan);
                }
            }
            Log::info('[cron][check clan members] Clan <' . $clan->name. '> -- Existing: '.
                count($existingMembers) . '. Query: ' . count($members) . ' Left: '.
                $leftClan . '. New: ' . $newMember);
        }
    }
}