<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceInterface;
use App\Models\BandwidthSample;
use App\Models\Flow;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class RealTimeBandwidthService
{
    private const CACHE_TTL = 60; // 1 minute

    /**
     * Calculate current bandwidth for a device
     */
    public function calculateDeviceBandwidth(Device $device): array
    {
        $cacheKey = "device_bandwidth:{$device->id}";

        return Cache::remember($cacheKey, 10, function () use ($device) {
            // Try BandwidthSample first
            $samples = BandwidthSample::forDevice($device->id)
                ->whereNull('interface_id')
                ->orderByDesc('sampled_at')
                ->limit(2)
                ->get();

            if ($samples->count() >= 2) {
                $current = $samples->first();
                $previous = $samples->last();

                $timeDiff = $current->sampled_at->diffInSeconds($previous->sampled_at);
                if ($timeDiff > 0) {
                    $byteDiff = ($current->in_bytes + $current->out_bytes) - ($previous->in_bytes + $previous->out_bytes);
                    $bps = (int)(($byteDiff * 8) / $timeDiff);

                    return [
                        'in_bps' => $current->in_bps,
                        'out_bps' => $current->out_bps,
                        'total_bps' => $current->in_bps + $current->out_bps,
                        'in_bytes' => $current->in_bytes,
                        'out_bytes' => $current->out_bytes,
                        'total_bytes' => $current->in_bytes + $current->out_bytes,
                        'total_formatted' => $this->formatBytes($current->in_bytes + $current->out_bytes),
                        'flow_count' => $current->flow_count,
                        'formatted' => $this->formatBandwidth($bps),
                        'sampled_at' => $current->sampled_at->toIso8601String(),
                    ];
                }
            }

            // Fallback: Calculate from flows in last hour
            $flowStats = Flow::where('device_id', $device->id)
                ->where('created_at', '>=', now()->subHour())
                ->selectRaw('COALESCE(SUM(bytes), 0) as total_bytes')
                ->selectRaw('COALESCE(SUM(packets), 0) as total_packets')
                ->selectRaw('COUNT(*) as flow_count')
                ->first();

            $totalBytes = (int)($flowStats->total_bytes ?? 0);
            $flowCount = (int)($flowStats->flow_count ?? 0);
            $bps = (int)(($totalBytes * 8) / 3600); // Average over the hour

            return [
                'in_bps' => (int)($bps / 2),
                'out_bps' => (int)($bps / 2),
                'total_bps' => $bps,
                'in_bytes' => (int)($totalBytes / 2),
                'out_bytes' => (int)($totalBytes / 2),
                'total_bytes' => $totalBytes,
                'total_formatted' => $this->formatBytes($totalBytes),
                'flow_count' => $flowCount,
                'formatted' => $this->formatBandwidth($bps),
                'sampled_at' => now()->toIso8601String(),
            ];
        });
    }

    /**
     * Calculate bandwidth for a specific interface
     */
    public function calculateInterfaceBandwidth(DeviceInterface $interface): array
    {
        $cacheKey = "interface_bandwidth:{$interface->id}";

        return Cache::remember($cacheKey, 10, function () use ($interface) {
            return [
                'in_bps' => $interface->in_bps ?? 0,
                'out_bps' => $interface->out_bps ?? 0,
                'total_bps' => ($interface->in_bps ?? 0) + ($interface->out_bps ?? 0),
                'in_utilization' => $interface->in_utilization ?? 0,
                'out_utilization' => $interface->out_utilization ?? 0,
                'speed' => $interface->speed ?? 0,
                'formatted_in' => $this->formatBandwidth($interface->in_bps ?? 0),
                'formatted_out' => $this->formatBandwidth($interface->out_bps ?? 0),
                'last_polled' => $interface->last_polled?->toIso8601String(),
            ];
        });
    }

    /**
     * Get sparkline data for a device
     * Returns array of objects with 'total' property for dashboard compatibility
     */
    public function getSparklineData(Device $device, int $points = 20, int $minutes = 30): array
    {
        $cacheKey = "sparkline:{$device->id}:{$points}:{$minutes}";

        return Cache::remember($cacheKey, 10, function () use ($device, $points, $minutes) {
            // Try BandwidthSample first
            $samples = BandwidthSample::forDevice($device->id)
                ->whereNull('interface_id')
                ->recent($minutes)
                ->orderBy('sampled_at', 'asc')
                ->get();

            if ($samples->count() > 0) {
                // If we have more samples than points, take evenly spaced samples
                if ($samples->count() > $points) {
                    $step = ceil($samples->count() / $points);
                    $samples = $samples->filter(fn($item, $key) => $key % $step === 0)->values();
                }

                // Return in format expected by dashboard sparklines
                return $samples->map(fn($s) => [
                    'total' => $s->in_bps + $s->out_bps,
                    'in' => $s->in_bps,
                    'out' => $s->out_bps,
                    'time' => $s->sampled_at->format('H:i'),
                ])->toArray();
            }

            // Fallback: Generate sparkline from flows grouped by minute
            $flowData = Flow::where('device_id', $device->id)
                ->where('created_at', '>=', now()->subMinutes($minutes))
                ->selectRaw("date_trunc('minute', created_at) as time_bucket")
                ->selectRaw('SUM(bytes) as total_bytes')
                ->groupBy('time_bucket')
                ->orderBy('time_bucket', 'asc')
                ->limit($points)
                ->get();

            if ($flowData->isEmpty()) {
                // Return empty sparkline data with placeholder
                return [];
            }

            return $flowData->map(function($row) {
                $bytes = (int)$row->total_bytes;
                $bps = (int)($bytes * 8 / 60); // Convert bytes per minute to bps
                return [
                    'total' => $bps,
                    'in' => (int)($bps / 2),
                    'out' => (int)($bps / 2),
                    'time' => \Carbon\Carbon::parse($row->time_bucket)->format('H:i'),
                ];
            })->toArray();
        });
    }

    /**
     * Get all devices bandwidth summary
     */
    public function getAllDevicesBandwidth(): Collection
    {
        return Device::online()->get()->map(function ($device) {
            $stats = $this->calculateDeviceBandwidth($device);
            return [
                'id' => $device->id,
                'name' => $device->name,
                'ip_address' => $device->ip_address,
                'status' => $device->status,
                'bandwidth' => $stats,
            ];
        });
    }

    /**
     * Get traffic summary for dashboard
     */
    public function getDashboardSummary(string $timeRange = '1hour'): array
    {
        $start = $this->getTimeRangeStart($timeRange);

        $flowStats = Flow::where('created_at', '>=', $start)
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('SUM(packets) as total_packets')
            ->selectRaw('COUNT(DISTINCT device_id) as device_count')
            ->first();

        $durationSeconds = now()->diffInSeconds($start);

        return [
            'flow_count' => $flowStats->flow_count ?? 0,
            'total_bytes' => $flowStats->total_bytes ?? 0,
            'total_packets' => $flowStats->total_packets ?? 0,
            'device_count' => $flowStats->device_count ?? 0,
            'avg_bandwidth_bps' => $durationSeconds > 0
                ? (int)((($flowStats->total_bytes ?? 0) * 8) / $durationSeconds)
                : 0,
            'formatted_bandwidth' => $durationSeconds > 0
                ? $this->formatBandwidth((int)((($flowStats->total_bytes ?? 0) * 8) / $durationSeconds))
                : '0 bps',
            'formatted_bytes' => $this->formatBytes($flowStats->total_bytes ?? 0),
        ];
    }

    /**
     * Get top talkers by bandwidth
     */
    public function getTopTalkers(string $timeRange = '1hour', int $limit = 10): array
    {
        $start = $this->getTimeRangeStart($timeRange);

        $sources = Flow::where('created_at', '>=', $start)
            ->selectRaw('source_ip as ip')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('COUNT(*) as flow_count')
            ->groupBy('source_ip')
            ->orderByDesc('total_bytes')
            ->limit($limit)
            ->get();

        $destinations = Flow::where('created_at', '>=', $start)
            ->selectRaw('destination_ip as ip')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('COUNT(*) as flow_count')
            ->groupBy('destination_ip')
            ->orderByDesc('total_bytes')
            ->limit($limit)
            ->get();

        return [
            'sources' => $sources->map(fn($s) => [
                'ip' => $s->ip,
                'bytes' => $s->total_bytes,
                'formatted' => $this->formatBytes($s->total_bytes),
                'flow_count' => $s->flow_count,
            ])->toArray(),
            'destinations' => $destinations->map(fn($d) => [
                'ip' => $d->ip,
                'bytes' => $d->total_bytes,
                'formatted' => $this->formatBytes($d->total_bytes),
                'flow_count' => $d->flow_count,
            ])->toArray(),
        ];
    }

    /**
     * Store a bandwidth sample manually
     */
    public function storeSample(Device $device, array $metrics, ?int $interfaceId = null): BandwidthSample
    {
        return BandwidthSample::create([
            'device_id' => $device->id,
            'interface_id' => $interfaceId,
            'in_bytes' => $metrics['in_bytes'] ?? 0,
            'out_bytes' => $metrics['out_bytes'] ?? 0,
            'in_packets' => $metrics['in_packets'] ?? 0,
            'out_packets' => $metrics['out_packets'] ?? 0,
            'in_bps' => $metrics['in_bps'] ?? 0,
            'out_bps' => $metrics['out_bps'] ?? 0,
            'flow_count' => $metrics['flow_count'] ?? 0,
            'sampled_at' => now(),
        ]);
    }

    /**
     * Get empty bandwidth stats structure
     */
    private function getEmptyBandwidthStats(): array
    {
        return [
            'in_bps' => 0,
            'out_bps' => 0,
            'total_bps' => 0,
            'in_bytes' => 0,
            'out_bytes' => 0,
            'total_bytes' => 0,
            'flow_count' => 0,
            'formatted' => '0 bps',
            'sampled_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Format bandwidth to human readable string
     */
    public function formatBandwidth(int $bps): string
    {
        if ($bps >= 1000000000) {
            return round($bps / 1000000000, 2) . ' Gbps';
        } elseif ($bps >= 1000000) {
            return round($bps / 1000000, 2) . ' Mbps';
        } elseif ($bps >= 1000) {
            return round($bps / 1000, 2) . ' Kbps';
        }
        return $bps . ' bps';
    }

    /**
     * Format bytes to human readable string
     */
    public function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * Convert time range string to Carbon date
     */
    private function getTimeRangeStart(string $timeRange): \Carbon\Carbon
    {
        return match ($timeRange) {
            '15min' => now()->subMinutes(15),
            '30min' => now()->subMinutes(30),
            '1hour' => now()->subHour(),
            '6hours' => now()->subHours(6),
            '24hours' => now()->subDay(),
            '7days' => now()->subDays(7),
            '30days' => now()->subDays(30),
            default => now()->subHour(),
        };
    }

    /**
     * Cleanup old samples
     */
    public function cleanup(int $keepDays = 7): int
    {
        return BandwidthSample::where('sampled_at', '<', now()->subDays($keepDays))->delete();
    }
}
