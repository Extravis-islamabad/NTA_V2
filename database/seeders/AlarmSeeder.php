<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\Alarm;

class AlarmSeeder extends Seeder
{
    public function run(): void
    {
        $devices = Device::all();
        
        $alarmTypes = [
            [
                'severity' => 'critical',
                'type' => 'high_utilization',
                'title' => 'High Interface Utilization',
                'description' => 'Interface utilization exceeded 90%'
            ],
            [
                'severity' => 'warning',
                'type' => 'bandwidth_spike',
                'title' => 'Bandwidth Spike Detected',
                'description' => 'Unusual traffic pattern detected'
            ],
            [
                'severity' => 'info',
                'type' => 'new_device',
                'title' => 'New Device Connected',
                'description' => 'A new device has been discovered on the network'
            ],
        ];

        foreach ($devices->take(2) as $device) {
            $alarm = $alarmTypes[array_rand($alarmTypes)];
            
            Alarm::create([
                'device_id' => $device->id,
                'severity' => $alarm['severity'],
                'type' => $alarm['type'],
                'title' => $alarm['title'],
                'description' => $alarm['description'] . " on device {$device->name}",
                'status' => 'active',
            ]);
        }
    }
}