<?php

namespace App\Jobs;
/**
 * This file is part of the isteam project.
 *
 * Date: 04/01/18 17:06
 * @author ionut
 */
use App\Administration\ClanActions;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Isteam\Wargaming\Api;

class CheckClanMembers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     * 
     * @param ClanActions $do
     * @param Api $api
     * @return void
     */
    public function handle(ClanActions $do, Api $api)
    {
        $do->checkMembers($api);
    }
}
