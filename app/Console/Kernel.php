<?php
namespace App\Console;

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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        // этот файл запускант supervisor или консольная команда artisan schedule:run
        // ежеминутно - общий файл для работ с базой
        $schedule->job(new DatabaseConsole)->everyMinute();
        // каждый день в 1 мин первого ночи по серверному времени
        $schedule->call(new LicenseConsole)->dailyAt('00:01');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
