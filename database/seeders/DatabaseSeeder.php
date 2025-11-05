<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Device;
use App\Models\DeviceInterface;
use App\Models\Flow;
use App\Models\Alarm;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@netflow.com',
            'password' => bcrypt('password'),
        ]);

        // Create devices
        $devices = [
            [
                'name' => 'BranchOffice_PaloAlto',
                'ip_address' => '10.5.5.8',
                'type' => 'palo_alto',
                'location' => 'Branch Office',
                'status' => 'online',
            ],
            [
                'name' => 'LosAngeles_CheckPoint',
                'ip_address' => '10.5.5.2',
                'type' => 'checkpoint',
                'location' => 'Los Angeles',
                'status' => 'online',
            ],
            [
                'name' => 'WirelessController',
                'ip_address' => '10.5.5.6',
                'type' => 'wireless_controller',
                'location' => 'Data Center',
                'status' => 'online',
            ],
        ];

        foreach ($devices as $deviceData) {
            $device = Device::create($deviceData);
            
            // Create interfaces for each device
            $interfaceCount = rand(2, 4);
            for ($i = 1; $i <= $interfaceCount; $i++) {
                DeviceInterface::create([
                    'device_id' => $device->id,
                    'name' => "GigabitEthernet0/$i",
                    'type' => 'ethernet',
                    'status' => $i == 1 ? 'up' : (rand(0, 1) ? 'up' : 'down'),
                    'speed' => 1000000000, // 1 Gbps
                    'in_octets' => rand(1000000000, 5000000000),
                    'out_octets' => rand(1000000000, 5000000000),
                    'description' => "Interface $i",
                ]);
            }

            // Create realistic flows for each device
            $applications = ['HTTP', 'HTTPS', 'SSH', 'FTP', 'DNS', 'SMTP', 'MySQL', 'PostgreSQL', 'Redis', 'MongoDB'];
            $protocols = ['TCP', 'UDP', 'ICMP'];
            $sourceIPs = [
                '192.168.1.10', '192.168.1.20', '192.168.1.30', '192.168.1.40', 
                '10.0.0.5', '10.0.0.10', '172.16.0.10', '172.16.0.20'
            ];
            $destIPs = [
                '8.8.8.8', '1.1.1.1', '208.67.222.222', '185.228.168.9',
                '13.107.42.14', '40.126.31.69', '52.96.84.82', '142.250.185.46'
            ];

            // Create 100 flows per device for the last hour
            for ($j = 0; $j < 100; $j++) {
                $bytes = rand(1024, 10485760); // 1KB to 10MB
                $packets = rand(10, 10000);
                
                Flow::create([
                    'device_id' => $device->id,
                    'source_ip' => $sourceIPs[array_rand($sourceIPs)],
                    'source_port' => rand(1024, 65535),
                    'destination_ip' => $destIPs[array_rand($destIPs)],
                    'destination_port' => rand(80, 8080),
                    'protocol' => $protocols[array_rand($protocols)],
                    'application' => $applications[array_rand($applications)],
                    'bytes' => $bytes,
                    'packets' => $packets,
                    'dscp' => rand(0, 63),
                    'created_at' => now()->subMinutes(rand(0, 60)),
                ]);
            }

            // Update device flow count
            $device->update(['flow_count' => 100]);
        }

        // Create some alarms
        $alarmTypes = ['bandwidth_spike', 'connection_failure', 'high_latency', 'packet_loss'];
        $severities = ['critical', 'warning', 'info'];

        foreach (Device::all() as $device) {
            for ($i = 0; $i < 2; $i++) {
                Alarm::create([
                    'device_id' => $device->id,
                    'title' => 'Bandwidth Spike Detected',
                    'description' => 'Unusual traffic pattern detected on device ' . $device->name,
                    'type' => $alarmTypes[array_rand($alarmTypes)],
                    'severity' => $severities[array_rand($severities)],
                    'status' => rand(0, 1) ? 'active' : 'acknowledged',
                    'created_at' => now()->subHours(rand(1, 24)),
                ]);
            }
        }

        $this->command->info('Database seeded successfully with realistic data!');
    }
}