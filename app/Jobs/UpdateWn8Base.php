<?php

namespace App\Jobs;
/**
 * This file is part of the isteam project.
 *
 * Date: 04/01/18 17:06
 * @author ionut
 */
use App\Administration\EncyclopediaActions;
use Illuminate\Bus\Queueable;
use Illuminate\Console\Application;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\App;
use Isteam\Wargaming\Api;

class UpdateWn8Base implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     * 
     * @param EncyclopediaActions $do
     * @param Api $api
     * @return void
     */
    public function handle(EncyclopediaActions $do)
    {
        $do->updateWn8Base(App('config')['isteam']);
    }
}
