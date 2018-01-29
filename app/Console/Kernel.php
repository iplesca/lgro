<?php

namespace App\Console;

use App\Jobs\CheckClanData;
use App\Jobs\CheckClanMembers;
use App\Jobs\CheckTankEncyclopedia;
use App\Jobs\SearchNewTanks;
use App\Jobs\UpdateMemberStats;
use App\Jobs\UpdateMemberWn8Values;
use App\Jobs\UpdateTankStats;
use App\Jobs\UpdateWn8Base;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;
use Isteam\Wargaming\Api;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (true || ('local' == env('APP_ENV') && 1 == env('APP_SCHEDULE', 0))) {
            $schedule->job(new UpdateWn8Base())
                ->withoutOverlapping();
            $schedule->job(new CheckTankEncyclopedia())
                ->withoutOverlapping();
            $schedule->job(new CheckClanData())
                ->withoutOverlapping();
            $schedule->job(new UpdateMemberStats())
                ->withoutOverlapping();
            $schedule->job(new UpdateTankStats())
                ->withoutOverlapping();
            $schedule->job(new UpdateMemberWn8Values())
                ->withoutOverlapping();
            $schedule->job(new SearchNewTanks())
                ->withoutOverlapping();
            $schedule->job(new CheckClanMembers)
                ->withoutOverlapping();
        }

        if ('production' == env('APP_ENV')) {
            $schedule->job(new UpdateWn8Base())->monthlyOn(1, '02:10')->withoutOverlapping();
            
            $schedule->job(new CheckTankEncyclopedia())
                ->dailyAt('02:00')->withoutOverlapping();
            $schedule->job(new CheckClanData())
                ->dailyAt('03:00')->withoutOverlapping();
            $schedule->job(new UpdateMemberStats())
                ->dailyAt('03:20')->withoutOverlapping();
            $schedule->job(new UpdateTankStats())
                ->dailyAt('03:40')->withoutOverlapping();
            $schedule->job(new UpdateMemberWn8Values())
                ->dailyAt('03:45')->withoutOverlapping();
            $schedule->job(new SearchNewTanks())
                ->dailyAt('03:50')->withoutOverlapping();

            $schedule->job(new CheckClanMembers)->hourlyAt(5)->withoutOverlapping();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
