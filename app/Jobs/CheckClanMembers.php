<?php

namespace App\Jobs;

use App\Clan;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Isteam\Wargaming\Api;

class CheckClanMembers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $api;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $clans = Clan::all();
        foreach ($clans as $clan) {
            $existingMembers = $clan->members()->get();
            $members = $api->server()->getClanMembers($clan->wargaming_id);

            // check for member that left the clan
            foreach ($existingMembers as $em) {
                // no longer present in the members list
                if (! isset($members[$em->wargaming_id])) {
                    $em->delete('[auto] no reason');
                }
            }
            // check for new members
            foreach ($members as $wargamingId => $m) {
                $member = $existingMembers->firstWhere('wargaming_id', $wargamingId);

                if (! $member) {
                    // re-add, in case it's a returning member
                    Member::readd($m, $clan);
                }
            }
        }
    }
}
