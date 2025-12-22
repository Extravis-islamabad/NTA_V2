<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Flow;
use App\Models\BandwidthSample;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NetflowCollectorService
{
    private const CACHE_TTL = 300; // 5 minutes

    private ApplicationIdentificationService $appService;
    private GeoLocationService $geoService;

    public function __construct(
        ApplicationIdentificationService $appService,
        GeoLocationService $geoService
    ) {
        $this->appService = $appService;
        $this->geoService = $geoService;
    }

    public function processNetflowPacket(array $data): void
    {
        try {
            $device = $this->getDevice($data['exporter_ip']);

            // Skip if device is not registered - only process flows for known devices
            if (!$device) {
                Log::debug("Ignoring NetFlow from unregistered device: {$data['exporter_ip']}");
                return;
            }

            $flowsCreated = 0;
            $totalBytes = 0;
            $totalPackets = 0;

            foreach ($data['flows'] as $flowData) {
                $this->createFlow($device, $flowData);
                $flowsCreated++;
                $totalBytes += $flowData['bytes'] ?? 0;
                $totalPackets += $flowData['packets'] ?? 0;
            }

            // Update device status to online and refresh last_seen_at
            Device::where('ip_address', $data['exporter_ip'])->update([
                'last_seen_at' => now(),
                'status' => 'online',
                'flow_count' => $device->flows()->count()
            ]);

            // Clear cache so fresh data is loaded
            Cache::forget("device:{$data['exporter_ip']}");

            // Update real-time stats and store bandwidth sample
            $this->updateRealTimeStats($device);
            $this->storeBandwidthSample($device, $totalBytes, $totalPackets, $flowsCreated);

        } catch (\Exception $e) {
            Log::error('Netflow processing error: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function getDevice(string $ipAddress): ?Device
    {
        return Cache::remember("device:{$ipAddress}", self::CACHE_TTL, function () use ($ipAddress) {
            // Only return existing devices - do NOT auto-create
            return Device::where('ip_address', $ipAddress)->first();
        });
    }

    private function createFlow(Device $device, array $flowData): void
    {
        // Enhanced application identification using full service
        $appInfo = $this->appService->identify(
            $flowData['src_ip'],
            $flowData['dst_ip'],
            $flowData['src_port'],
            $flowData['dst_port'],
            $this->getProtocolName($flowData['protocol'])
        );

        // Geolocation lookup for source IP
        $srcGeo = $this->geoService->lookup($flowData['src_ip']);

        // Geolocation lookup for destination IP
        $dstGeo = $this->geoService->lookup($flowData['dst_ip']);

        // Domain identification for HTTP/HTTPS traffic
        $dstDomain = null;
        $srcDomain = null;

        // For known applications, use the app name as domain hint
        if ($appInfo['name'] !== 'Unknown' && $appInfo['category'] !== 'Unknown') {
            $dstDomain = strtolower($appInfo['name']) . '.com';
        }

        // Try reverse DNS for destination (for HTTP/HTTPS traffic)
        $dstPort = (int)$flowData['dst_port'];
        if (in_array($dstPort, [80, 443, 8080, 8443]) && $dstDomain === null) {
            $dstDomain = $this->resolveDomain($flowData['dst_ip']);
        }

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
            'application' => $appInfo['name'],
            'app_category' => $appInfo['category'],
            'dscp' => $flowData['dscp'] ?? null,

            // Interface fields
            'input_interface' => $flowData['input_interface'] ?? $flowData['in_if'] ?? null,
            'output_interface' => $flowData['output_interface'] ?? $flowData['out_if'] ?? null,

            // Source geolocation
            'src_country_code' => $srcGeo['country_code'] ?? null,
            'src_country_name' => $srcGeo['country_name'] ?? null,
            'src_city' => $srcGeo['city'] ?? null,
            'src_latitude' => $srcGeo['latitude'] ?? null,
            'src_longitude' => $srcGeo['longitude'] ?? null,
            'src_asn' => $srcGeo['asn'] ?? null,

            // Destination geolocation
            'dst_country_code' => $dstGeo['country_code'] ?? null,
            'dst_country_name' => $dstGeo['country_name'] ?? null,
            'dst_city' => $dstGeo['city'] ?? null,
            'dst_latitude' => $dstGeo['latitude'] ?? null,
            'dst_longitude' => $dstGeo['longitude'] ?? null,
            'dst_asn' => $dstGeo['asn'] ?? null,

            // Domain identification
            'dst_domain' => $dstDomain,
            'src_domain' => $srcDomain,
        ]);
    }

    /**
     * Resolve domain name from IP using reverse DNS with caching
     */
    private function resolveDomain(string $ip): ?string
    {
        // Skip private IPs
        if ($this->isPrivateIP($ip)) {
            return null;
        }

        // Check cache first
        $cacheKey = "dns_reverse:{$ip}";
        $cached = Cache::get($cacheKey);

        if ($cached !== null) {
            return $cached === '' ? null : $cached;
        }

        try {
            $host = @gethostbyaddr($ip);

            // gethostbyaddr returns the IP if no hostname found
            if ($host && $host !== $ip) {
                // Clean up the hostname to get the domain
                $parts = explode('.', $host);
                if (count($parts) >= 2) {
                    // Get the last two parts as domain
                    $domain = implode('.', array_slice($parts, -2));
                    Cache::put($cacheKey, $domain, 3600); // Cache for 1 hour
                    return $domain;
                }
                Cache::put($cacheKey, $host, 3600);
                return $host;
            }
        } catch (\Exception $e) {
            Log::debug("DNS reverse lookup failed for {$ip}: " . $e->getMessage());
        }

        // Cache empty result to avoid repeated lookups
        Cache::put($cacheKey, '', 1800); // Cache failures for 30 minutes
        return null;
    }

    /**
     * Check if IP is private/internal
     */
    private function isPrivateIP(string $ip): bool
    {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
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

    private function storeBandwidthSample(Device $device, int $bytes, int $packets, int $flowCount): void
    {
        // Get previous sample to calculate rate
        $prevSample = BandwidthSample::where('device_id', $device->id)
            ->whereNull('interface_id')
            ->orderByDesc('sampled_at')
            ->first();

        $inBps = 0;
        $outBps = 0;

        if ($prevSample) {
            $timeDiff = now()->diffInSeconds($prevSample->sampled_at);
            if ($timeDiff > 0) {
                // Calculate bits per second (bytes * 8 / seconds)
                $inBps = (int)(($bytes * 8) / $timeDiff);
                $outBps = $inBps; // For NetFlow we don't distinguish in/out at device level
            }
        }

        BandwidthSample::create([
            'device_id' => $device->id,
            'interface_id' => null,
            'in_bytes' => $bytes,
            'out_bytes' => $bytes,
            'in_packets' => $packets,
            'out_packets' => $packets,
            'in_bps' => $inBps,
            'out_bps' => $outBps,
            'flow_count' => $flowCount,
            'sampled_at' => now(),
        ]);
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
