<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Isteam\Wargaming\Api;
use App\Administration\ClanActions;

class CheckClanData implements ShouldQueue
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
        $do->updateData($api);
    }
}
