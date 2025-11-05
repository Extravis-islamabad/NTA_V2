<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\NetflowListener::class,
        Commands\AggregateTraffic::class,
        Commands\CheckDeviceHealth::class,
        Commands\SetupDevice::class,  // Add this line
        Commands\FlowSimulator::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Aggregate traffic every 10 minutes
        $schedule->command('traffic:aggregate 10min')->everyTenMinutes();
        
        // Aggregate hourly traffic
        $schedule->command('traffic:aggregate 1hour')->hourly();
        
        // Aggregate daily traffic
        $schedule->command('traffic:aggregate 1day')->daily();
        
        // Check device health every 5 minutes
        $schedule->command('device:health-check')->everyFiveMinutes();
        
        // Clean old flow data (keep last 7 days)
        $schedule->call(function () {
            \App\Models\Flow::where('created_at', '<', now()->subDays(7))->delete();
        })->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}