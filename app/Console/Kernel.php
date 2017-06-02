<?php

namespace App\Console;

use App\Console\Commands\BtcWatch;
use App\Console\Commands\HuobiAutoLow;
use App\Console\Commands\HuobiGet;
use App\Console\Commands\HuobiSet;
use App\Console\Commands\HuobiWatch;
use App\Console\Commands\LtcWatch;
use App\Console\Commands\OrderSync;
use App\Console\Commands\PusherTest;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        HuobiWatch::class,
        OrderSync::class,
        PusherTest::class,
        HuobiSet::class,
        HuobiGet::class,
        HuobiAutoLow::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
