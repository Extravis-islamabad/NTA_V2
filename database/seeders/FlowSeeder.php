<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\Flow;
use App\Models\TrafficStatistic;
use Carbon\Carbon;

class FlowSeeder extends Seeder
{
    public function run(): void
    {
        $devices = Device::all();

        foreach ($devices as $device) {
            // Generate flows for the last hour
            $this->generateFlowsForDevice($device, 100);
            
            // Generate traffic statistics
            $this->generateTrafficStatistics($device);
        }
    }

    private function generateFlowsForDevice(Device $device, int $count): void
    {
        $applications = ['HTTP', 'HTTPS', 'SSH', 'FTP', 'DNS', 'SMTP', 'MySQL', 'RDP', null];
        $protocols = ['TCP', 'UDP', 'ICMP'];
        $sourceIps = ['192.168.1.10', '192.168.1.20', '192.168.1.30', '10.0.0.5', '10.0.0.15'];
        $destIps = ['8.8.8.8', '1.1.1.1', '192.168.1.1', '10.0.0.1', '172.16.0.1'];

        for ($i = 0; $i < $count; $i++) {
            $createdAt = Carbon::now()->subMinutes(rand(1, 60));
            
            Flow::create([
                'device_id' => $device->id,
                'source_ip' => $sourceIps[array_rand($sourceIps)],
                'destination_ip' => $destIps[array_rand($destIps)],
                'source_port' => rand(1024, 65535),
                'destination_port' => $this->getPortForApp($applications[array_rand($applications)]),
                'protocol' => $protocols[array_rand($protocols)],
                'bytes' => rand(1000, 10000000),
                'packets' => rand(10, 10000),
                'first_switched' => $createdAt,
                'last_switched' => $createdAt->copy()->addSeconds(rand(1, 300)),
                'application' => $applications[array_rand($applications)],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // Update device flow count
        $device->update(['flow_count' => $device->flows()->count()]);
    }

    private function getPortForApp(?string $app): int
    {
        $ports = [
            'HTTP' => 80,
            'HTTPS' => 443,
            'SSH' => 22,
            'FTP' => 21,
            'DNS' => 53,
            'SMTP' => 25,
            'MySQL' => 3306,
            'RDP' => 3389,
        ];

        return $ports[$app] ?? rand(1024, 65535);
    }

    private function generateTrafficStatistics(Device $device): void
    {
        $intervalStart = Carbon::now()->subHour();
        $intervalEnd = Carbon::now();

        // Protocol statistics
        $protocolStats = [
            ['protocol' => 'TCP', 'bytes' => rand(50000000, 200000000), 'packets' => rand(50000, 200000)],
            ['protocol' => 'UDP', 'bytes' => rand(10000000, 80000000), 'packets' => rand(10000, 80000)],
            ['protocol' => 'ICMP', 'bytes' => rand(1000000, 10000000), 'packets' => rand(1000, 10000)],
        ];

        foreach ($protocolStats as $stat) {
            TrafficStatistic::create([
                'device_id' => $device->id,
                'protocol' => $stat['protocol'],
                'total_bytes' => $stat['bytes'],
                'total_packets' => $stat['packets'],
                'flow_count' => rand(100, 1000),
                'avg_speed_bps' => $stat['bytes'] * 8 / 3600,
                'max_speed_bps' => ($stat['bytes'] * 8 / 3600) * 1.5,
                'interval_type' => '1hour',
                'interval_start' => $intervalStart,
                'interval_end' => $intervalEnd,
            ]);
        }

        // Application statistics
        $appStats = [
            ['app' => 'HTTPS', 'bytes' => rand(80000000, 150000000), 'packets' => rand(80000, 150000)],
            ['app' => 'HTTP', 'bytes' => rand(30000000, 60000000), 'packets' => rand(30000, 60000)],
            ['app' => 'SSH', 'bytes' => rand(5000000, 20000000), 'packets' => rand(5000, 20000)],
            ['app' => 'DNS', 'bytes' => rand(2000000, 10000000), 'packets' => rand(2000, 10000)],
            ['app' => 'FTP', 'bytes' => rand(10000000, 40000000), 'packets' => rand(10000, 40000)],
        ];

        foreach ($appStats as $stat) {
            TrafficStatistic::create([
                'device_id' => $device->id,
                'application' => $stat['app'],
                'total_bytes' => $stat['bytes'],
                'total_packets' => $stat['packets'],
                'flow_count' => rand(50, 500),
                'avg_speed_bps' => $stat['bytes'] * 8 / 3600,
                'max_speed_bps' => ($stat['bytes'] * 8 / 3600) * 1.5,
                'interval_type' => '1hour',
                'interval_start' => $intervalStart,
                'interval_end' => $intervalEnd,
            ]);
        }
    }
}