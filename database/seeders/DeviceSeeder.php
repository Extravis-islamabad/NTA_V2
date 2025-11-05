<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\DeviceInterface as DeviceInterface;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            [
                'name' => 'BranchOffice_PaloAlto',
                'ip_address' => '10.5.5.8',
                'type' => 'palo_alto',
                'location' => 'Branch Office',
                'status' => 'online',
                'device_group' => 'Branch Office Devices',
                'last_seen_at' => now()
            ],
            [
                'name' => 'LosAngeles_CheckPoint',
                'ip_address' => '10.5.5.2',
                'type' => 'checkpoint',
                'location' => 'Los Angeles',
                'status' => 'online',
                'device_group' => 'Branch Office Devices',
                'last_seen_at' => now()
            ],
            [
                'name' => 'WirelessController',
                'ip_address' => '10.5.5.6',
                'type' => 'wireless_controller',
                'location' => 'HQ',
                'status' => 'online',
                'device_group' => 'Wifi Devices',
                'last_seen_at' => now()
            ],
        ];

        foreach ($devices as $deviceData) {
            $device = Device::create($deviceData);

            // Create interfaces for each device
            DeviceInterface::create([
                'device_id' => $device->id,
                'name' => 'Gigabit Ethernet 0/0',
                'description' => 'Main uplink',
                'type' => 'gigabit',
                'status' => 'up',
                'speed_bps' => 1000000000, // 1 Gbps
                'utilization_percent' => rand(10, 80),
                'in_octets' => rand(1000000, 10000000),
                'out_octets' => rand(1000000, 10000000),
            ]);

            if ($device->type !== 'wireless_controller') {
                DeviceInterface::create([
                    'device_id' => $device->id,
                    'name' => 'Gigabit Ethernet 0/1',
                    'description' => 'Secondary link',
                    'type' => 'gigabit',
                    'status' => 'up',
                    'speed_bps' => 1000000000,
                    'utilization_percent' => rand(5, 50),
                    'in_octets' => rand(500000, 5000000),
                    'out_octets' => rand(500000, 5000000),
                ]);
            }

            $device->update(['interface_count' => $device->interfaces()->count()]);
        }
    }
}