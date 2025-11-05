<?php

namespace App\Services;

use App\Models\Alarm;
use App\Models\Device;
use Illuminate\Support\Facades\Log;

class AlarmService
{
    public function checkDeviceHealth(Device $device): void
    {
        // Check if device is offline
        if ($device->status === 'offline') {
            $this->createAlarm($device, 'critical', 'device_offline', 
                'Device Offline', 
                "Device {$device->name} ({$device->ip_address}) is offline"
            );
        }

        // Check high traffic
        $recentTraffic = $device->flows()
            ->where('created_at', '>=', now()->subMinutes(5))
            ->sum('bytes');

        if ($recentTraffic > 1073741824) { // 1GB in 5 minutes
            $this->createAlarm($device, 'warning', 'high_traffic',
                'High Traffic Detected',
                "Device {$device->name} has high traffic: " . $this->formatBytes($recentTraffic)
            );
        }
    }

    private function createAlarm(
        Device $device,
        string $severity,
        string $type,
        string $title,
        string $description
    ): void {
        // Check if similar alarm already exists
        $existingAlarm = Alarm::where('device_id', $device->id)
            ->where('type', $type)
            ->where('status', 'active')
            ->first();

        if (!$existingAlarm) {
            Alarm::create([
                'device_id' => $device->id,
                'severity' => $severity,
                'type' => $type,
                'title' => $title,
                'description' => $description,
                'status' => 'active'
            ]);

            Log::info("Alarm created: {$title}", [
                'device' => $device->name,
                'severity' => $severity
            ]);
        }
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        return round($bytes / 1024, 2) . ' KB';
    }
}