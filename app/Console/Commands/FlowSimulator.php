<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Device;
use App\Models\Flow;

class FlowSimulator extends Command
{
    protected $signature = 'flow:simulate 
                            {--device= : Device ID to simulate flows for}
                            {--count=50 : Number of flows to generate}
                            {--continuous : Keep generating flows continuously}';
    
    protected $description = 'Simulate NetFlow data for testing';

    public function handle()
    {
        $deviceId = $this->option('device');
        $count = $this->option('count');
        $continuous = $this->option('continuous');

        if ($deviceId) {
            $device = Device::find($deviceId);
            if (!$device) {
                $this->error("Device with ID {$deviceId} not found!");
                return 1;
            }
            $devices = collect([$device]);
        } else {
            $devices = Device::all();
            if ($devices->isEmpty()) {
                $this->error('No devices found! Please add a device first.');
                $this->info('Run: php artisan device:add "DeviceName" "IP" "type"');
                return 1;
            }
        }

        $this->info("🚀 Starting NetFlow Simulator...");
        $this->info("Generating flows for " . $devices->count() . " device(s)");
        
        if ($continuous) {
            $this->info("Press Ctrl+C to stop\n");
        }
        
        $iteration = 0;
        do {
            $iteration++;
            
            foreach ($devices as $device) {
                $this->generateFlows($device, $count);
                $this->info("✓ Generated {$count} flows for {$device->name} (Iteration: {$iteration})");
            }

            if ($continuous) {
                $this->line("Waiting 10 seconds before next batch...\n");
                sleep(10);
            }
        } while ($continuous);

        $this->info("\n✅ Flow simulation completed!");
        return 0;
    }

    private function generateFlows(Device $device, int $count)
    {
        $applications = [
            'HTTP', 'HTTPS', 'SSH', 'FTP', 'DNS', 'SMTP', 'IMAP', 
            'MySQL', 'PostgreSQL', 'RDP', 'SFTP', 'MongoDB', 'Redis',
            'Telnet', 'SNMP', 'NTP', 'LDAP', 'SMB', 'WebSocket'
        ];
        
        $protocols = ['TCP', 'UDP', 'ICMP'];
        
        $sourceIPs = [
            '192.168.1.10', '192.168.1.20', '192.168.1.30', '192.168.1.40', '192.168.1.50',
            '192.168.1.100', '192.168.1.101', '192.168.1.102', '192.168.1.103',
            '10.0.0.5', '10.0.0.10', '10.0.0.15', '10.0.0.20', '10.0.0.25',
            '172.16.0.10', '172.16.0.20', '172.16.0.30', '172.16.0.40'
        ];
        
        $destIPs = [
            // Google
            '8.8.8.8', '8.8.4.4', '142.250.185.46',
            // Cloudflare
            '1.1.1.1', '1.0.0.1',
            // OpenDNS
            '208.67.222.222', '208.67.220.220',
            // AWS
            '13.107.42.14', '52.96.84.82',
            // Azure
            '40.126.31.69', '40.112.72.205',
            // Google Cloud
            '34.149.100.209', '35.190.247.0',
            // Akamai
            '23.1.106.61',
            // Generic
            '185.228.168.9', '195.154.178.5'
        ];

        $commonPorts = [
            80, 443, 22, 21, 25, 53, 110, 143, 
            3306, 5432, 3389, 23, 161, 123, 389, 445
        ];

        $flows = [];
        $now = now();
        
        for ($i = 0; $i < $count; $i++) {
            $protocol = $protocols[array_rand($protocols)];
            $application = $applications[array_rand($applications)];
            $bytes = rand(1024, 10485760); // 1KB to 10MB
            $packets = rand(10, 10000);
            $sourcePort = rand(1024, 65535);
            $destPort = $commonPorts[array_rand($commonPorts)];
            
            // Randomize timestamp within last hour
            $minutesAgo = rand(0, 59);
            $timestamp = $now->copy()->subMinutes($minutesAgo);
            
            $flows[] = [
                'device_id' => $device->id,
                'source_ip' => $sourceIPs[array_rand($sourceIPs)],
                'source_port' => $sourcePort,
                'destination_ip' => $destIPs[array_rand($destIPs)],
                'destination_port' => $destPort,
                'protocol' => $protocol,
                'application' => $application,
                'bytes' => $bytes,
                'packets' => $packets,
                'dscp' => rand(0, 63),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        // Batch insert for performance
        Flow::insert($flows);
        
        // Update device statistics
        $totalFlows = $device->flows()->count();
        $device->update([
            'flow_count' => $totalFlows,
            'status' => 'online',
            'last_seen' => now(),
        ]);
    }
}