<?php

namespace App\Console\Commands;

use App\Administration\PlayerActions;
use App\Models\Member;
use Illuminate\Console\Command;

class SyncUserTanks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:user-tanks ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->signature .= '{accountId : User account id}';

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param PlayerActions $playerActions
     */
    public function handle(PlayerActions $playerActions)
    {
        $accountId = $this->argument('accountId');
        $force = true;

        $member = Member::where('account_id', $accountId)->first();
        if (! is_null($member)) {
            $this->info('Found member ' . $member->nickname);
            $tanksData = $playerActions->getTanks($member);
            $this->info('Tanks to create/update: ' . count($tanksData));
            $tanks = $member->createTanks($tanksData, $force);

            $playerActions->updateTankListStats($member, $tanks);
        }
    }
}
