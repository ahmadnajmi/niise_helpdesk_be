<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('holidays', [date('Y')])->yearlyOn(12, 30, '00:00');
        $schedule->command('holidays', [date('Y')])->dailyAt('18:50');
        $schedule->command('read_emails_incident')->everyFiveMinutes();


    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
