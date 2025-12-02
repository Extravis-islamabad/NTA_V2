<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Flow;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NetflowCollectorService
{
    private const CACHE_TTL = 300; // 5 minutes

    public function processNetflowPacket(array $data): void
    {
        try {
            $device = $this->getOrCreateDevice($data['exporter_ip']);

            foreach ($data['flows'] as $flowData) {
                $this->createFlow($device, $flowData);
            }

            // Update device status to online and refresh last_seen_at
            Device::where('ip_address', $data['exporter_ip'])->update([
                'last_seen_at' => now(),
                'status' => 'online',
                'flow_count' => $device->flows()->count()
            ]);

            // Clear cache so fresh data is loaded
            Cache::forget("device:{$data['exporter_ip']}");

            $this->updateRealTimeStats($device);
        } catch (\Exception $e) {
            Log::error('Netflow processing error: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function getOrCreateDevice(string $ipAddress): Device
    {
        return Cache::remember("device:{$ipAddress}", self::CACHE_TTL, function () use ($ipAddress) {
            return Device::firstOrCreate(
                ['ip_address' => $ipAddress],
                [
                    'name' => "Device_{$ipAddress}",
                    'type' => 'router',
                    'status' => 'online',
                    'last_seen_at' => now()
                ]
            );
        });
    }

    private function createFlow(Device $device, array $flowData): void
    {
        Flow::create([
            'device_id' => $device->id,
            'source_ip' => $flowData['src_ip'],
            'destination_ip' => $flowData['dst_ip'],
            'source_port' => $flowData['src_port'],
            'destination_port' => $flowData['dst_port'],
            'protocol' => $this->getProtocolName($flowData['protocol']),
            'bytes' => $flowData['bytes'],
            'packets' => $flowData['packets'],
            'first_switched' => $flowData['first_switched'] ?? now(),
            'last_switched' => $flowData['last_switched'] ?? now(),
            'application' => $this->identifyApplication($flowData),
        ]);
    }

    private function getProtocolName(int $protocolNumber): string
    {
        $protocols = [
            1 => 'ICMP',
            6 => 'TCP',
            17 => 'UDP',
            47 => 'GRE',
            50 => 'ESP',
            51 => 'AH',
            89 => 'OSPF',
            132 => 'SCTP',
        ];

        return $protocols[$protocolNumber] ?? "PROTO_{$protocolNumber}";
    }

    private function identifyApplication(array $flowData): ?string
    {
        $port = $flowData['dst_port'];
        
        $applications = [
            20 => 'FTP-DATA',
            21 => 'FTP',
            22 => 'SSH',
            23 => 'Telnet',
            25 => 'SMTP',
            53 => 'DNS',
            80 => 'HTTP',
            110 => 'POP3',
            143 => 'IMAP',
            443 => 'HTTPS',
            445 => 'SMB',
            3306 => 'MySQL',
            3389 => 'RDP',
            5432 => 'PostgreSQL',
            6379 => 'Redis',
            8080 => 'HTTP-Alt',
            27017 => 'MongoDB',
        ];

        return $applications[$port] ?? null;
    }

    private function updateRealTimeStats(Device $device): void
    {
        $cacheKey = "realtime_stats:{$device->id}";
        
        $stats = [
            'total_flows' => $device->flows()->where('created_at', '>=', now()->subMinutes(1))->count(),
            'total_bytes' => $device->flows()->where('created_at', '>=', now()->subMinutes(1))->sum('bytes'),
            'total_packets' => $device->flows()->where('created_at', '>=', now()->subMinutes(1))->sum('packets'),
            'updated_at' => now()->toIso8601String()
        ];

        Cache::put($cacheKey, $stats, self::CACHE_TTL);
    }

    public function getRealTimeStats(Device $device): array
    {
        $cacheKey = "realtime_stats:{$device->id}";
        
        return Cache::get($cacheKey, [
            'total_flows' => 0,
            'total_bytes' => 0,
            'total_packets' => 0,
            'updated_at' => now()->toIso8601String()
        ]);
    }
}