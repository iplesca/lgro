<?php

namespace App\Jobs;

use App\Clan;
use App\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Isteam\Wargaming\Api;

class CheckClanMembers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     * 
     * @param Api $api
     * @return void
     */
    public function handle(Api $api)
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
