<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Device;

class SetupDevice extends Command
{
    protected $signature = 'device:add 
                            {name : Device name}
                            {ip : Device IP address}
                            {type : Device type (cisco_router/router/switch/firewall/fortigate/checkpoint/palo_alto/wireless_controller)}
                            {--location= : Device location}
                            {--group= : Device group}';
    
    protected $description = 'Quickly add a new device via command line';

    public function handle()
    {
        try {
            $device = Device::create([
                'name' => $this->argument('name'),
                'ip_address' => $this->argument('ip'),
                'type' => $this->argument('type'),
                'location' => $this->option('location'),
                'device_group' => $this->option('group'),
                'status' => 'offline',
            ]);

            $this->info("✓ Device '{$device->name}' added successfully!");
            $this->info("  ID: {$device->id}");
            $this->info("  IP: {$device->ip_address}");
            $this->info("  Type: " . ucfirst(str_replace('_', ' ', $device->type)));
            
            $this->line("\n" . str_repeat('=', 60));
            $this->info("📋 Configure NetFlow on your device:");
            $this->line(str_repeat('=', 60));
            
            $this->showConfiguration($device);
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }

    private function showConfiguration(Device $device)
    {
        $serverIp = gethostbyname(gethostname());
        
        switch ($device->type) {
            case 'cisco_router':
            case 'router':
            case 'switch':
                $this->showCiscoConfig($serverIp);
                break;
            
            case 'fortigate':
                $this->showFortiGateConfig($serverIp);
                break;
            
            case 'palo_alto':
                $this->showPaloAltoConfig($serverIp);
                break;
            
            case 'checkpoint':
                $this->showCheckPointConfig($serverIp);
                break;
            
            default:
                $this->showGenericConfig($serverIp);
                break;
        }
    }

    private function showCiscoConfig($serverIp)
    {
        $this->line("\nCisco Router/Switch Configuration:");
        $this->line("----------------------------------");
        $config = <<<EOT

flow exporter NETFLOW-EXPORTER
 destination {$serverIp}
 transport udp 9995

flow record NETFLOW-RECORD
 match ipv4 source address
 match ipv4 destination address
 match ipv4 protocol
 match transport source-port
 match transport destination-port
 collect counter bytes
 collect counter packets

flow monitor NETFLOW-MONITOR
 record NETFLOW-RECORD
 exporter NETFLOW-EXPORTER

interface GigabitEthernet0/0
 ip flow monitor NETFLOW-MONITOR input
 ip flow monitor NETFLOW-MONITOR output

! Verify with:
show flow exporter
show flow monitor
EOT;
        $this->line($config);
    }

    private function showFortiGateConfig($serverIp)
    {
        $this->line("\nFortiGate Firewall Configuration:");
        $this->line("----------------------------------");
        $config = <<<EOT

CLI Method:
-----------
config system netflow
    set collector-ip {$serverIp}
    set collector-port 9995
    set source-ip 0.0.0.0
    set active-flow-timeout 30
    set inactive-flow-timeout 15
end

config system interface
    edit "port1"
        set netflow-sampler both
    next
end

GUI Method:
-----------
1. System > Feature Visibility > Enable NetFlow
2. Log & Report > Log Settings > NetFlow
3. Create New collector:
   - IP: {$serverIp}
   - Port: 9995
4. Policy & Objects > Firewall Policy
5. Edit policies and enable NetFlow logging

Verify with:
diagnose test application sflowd 2
EOT;
        $this->line($config);
    }

    private function showPaloAltoConfig($serverIp)
    {
        $this->line("\nPalo Alto Firewall Configuration:");
        $this->line("----------------------------------");
        $config = <<<EOT

GUI Configuration:
------------------
1. Device > Server Profiles > NetFlow
2. Click 'Add' and create profile
3. Add NetFlow Server:
   - Name: NTA-Server
   - NetFlow Server: {$serverIp}
   - Port: 9995
4. Objects > Log Forwarding
5. Create/edit profile and add NetFlow server
6. Apply to security policies
EOT;
        $this->line($config);
    }

    private function showCheckPointConfig($serverIp)
    {
        $this->line("\nCheck Point Firewall Configuration:");
        $this->line("------------------------------------");
        $config = <<<EOT

1. SmartConsole > Manage & Settings > Blades > Network Policy Management
2. Global Properties > Log and Alert > NetFlow
3. Enable NetFlow and configure:
   - Target IP: {$serverIp}
   - Port: 9995
4. Install policy
EOT;
        $this->line($config);
    }

    private function showGenericConfig($serverIp)
    {
        $this->line("\nGeneric NetFlow Configuration:");
        $this->line("-------------------------------");
        $config = <<<EOT

Server Details:
--------------
NetFlow Collector IP: {$serverIp}
Port: 9995
Protocol: UDP
Version: NetFlow v5 or v9

Configuration Steps:
-------------------
1. Access device management interface
2. Navigate to NetFlow/sFlow settings
3. Enable NetFlow export
4. Configure collector as above
5. Apply to desired interfaces
6. Save and commit changes
EOT;
        $this->line($config);
    }
}