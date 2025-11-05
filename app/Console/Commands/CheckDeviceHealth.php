<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Device;
use App\Services\AlarmService;

class CheckDeviceHealth extends Command
{
    protected $signature = 'device:health-check';
    protected $description = 'Check device health and create alarms';

    private AlarmService $alarmService;

    public function __construct(AlarmService $alarmService)
    {
        parent::__construct();
        $this->alarmService = $alarmService;
    }

    public function handle()
    {
        $this->info('Checking device health...');
        
        $devices = Device::all();
        
        foreach ($devices as $device) {
            $this->info("Checking: {$device->name}");
            
            // Update device status
            $device->updateStatus();
            
            // Check health and create alarms
            $this->alarmService->checkDeviceHealth($device);
        }

        $this->info('✓ Device health check completed');
        return 0;
    }
}