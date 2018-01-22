<?php

namespace App\Console;

use App\Jobs\CheckClanData;
use App\Jobs\CheckClanMembers;
use App\Jobs\CheckTankEncyclopedia;
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
//        $schedule->job(new CheckTankEncyclopedia())->everyMinute()->withoutOverlapping();
//        $schedule->job(new UpdateWn8Base())->everyMinute()->withoutOverlapping();
//        $schedule->job(new CheckClanMembers)->everyMinute()->withoutOverlapping();
//        $schedule->job(new CheckClanData())->everyMinute()->withoutOverlapping();
//        $schedule->job(new UpdateMemberStats())->everyMinute()->withoutOverlapping();
//        $schedule->job(new UpdateTankStats())->everyMinute()->withoutOverlapping();
//        $schedule->job(new UpdateMemberWn8Values())->everyMinute()->withoutOverlapping();


//        $schedule->job(new CheckClanMembers)->hourlyAt(5)->withoutOverlapping();
//        $schedule->job(new CheckClanData())->dailyAt('03:00')->withoutOverlapping();
//        $schedule->job(new UpdateMemberStats())->dailyAt('03:05')->withoutOverlapping();
//        $schedule->job(new UpdateTankStats())->dailyAt('03:10')->withoutOverlapping();
//        $schedule->job(new UpdateMemberWn8Values())->dailyAt('03:15')->withoutOverlapping();
//        $schedule->job(new CheckTankEncyclopedia())->mothlyOn(1, '02:00')->withoutOverlapping();
//        $schedule->job(new UpdateWn8Base())->monthlyOn(1, '02:10')->withoutOverlapping();
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
