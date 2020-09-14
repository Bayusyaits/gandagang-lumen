<?php

/**
 * Kernel.
 *
 * @category ConsoleKernel
 *
 * @author   Display Name bayusyaits@gandagang.com
 *
 * @param    string  $schedule
 * @param    string  $commands
 * @return   void
 *
 * @throws \Exception
 */

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

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
     * @param string $schedule
     *
     * @return jobkernel
     */
    protected function schedule(Schedule $schedule)
    {
        //
        include 'app/Console/Kernel/JobsKernel.php';
    }
}
