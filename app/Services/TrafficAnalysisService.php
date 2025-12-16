<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Flow;
use App\Models\TrafficStatistic;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrafficAnalysisService
{
    public function aggregateTrafficData(string $intervalType = '10min'): void
    {
        $intervals = [
            '1min' => 1,
            '10min' => 10,
            '1hour' => 60,
            '1day' => 1440,
        ];

        $minutes = $intervals[$intervalType] ?? 10;
        $intervalStart = now()->subMinutes($minutes);
        $intervalEnd = now();

        $devices = Device::online()->get();

        foreach ($devices as $device) {
            $this->aggregateDeviceTraffic($device, $intervalType, $intervalStart, $intervalEnd);
        }
    }

    private function aggregateDeviceTraffic(
        Device $device,
        string $intervalType,
        Carbon $intervalStart,
        Carbon $intervalEnd
    ): void {
        // Aggregate by protocol
        $protocolStats = Flow::where('device_id', $device->id)
            ->whereBetween('created_at', [$intervalStart, $intervalEnd])
            ->select('protocol')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('SUM(packets) as total_packets')
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('AVG(bytes) as avg_bytes')
            ->selectRaw('MAX(bytes) as max_bytes')
            ->groupBy('protocol')
            ->get();

        foreach ($protocolStats as $stat) {
            $duration = max(1, $intervalEnd->diffInSeconds($intervalStart));
            
            TrafficStatistic::create([
                'device_id' => $device->id,
                'protocol' => $stat->protocol,
                'total_bytes' => $stat->total_bytes,
                'total_packets' => $stat->total_packets,
                'flow_count' => $stat->flow_count,
                'avg_speed_bps' => ($stat->avg_bytes * 8) / $duration,
                'max_speed_bps' => ($stat->max_bytes * 8) / $duration,
                'interval_type' => $intervalType,
                'interval_start' => $intervalStart,
                'interval_end' => $intervalEnd,
            ]);
        }

        // Aggregate by application
        $appStats = Flow::where('device_id', $device->id)
            ->whereBetween('created_at', [$intervalStart, $intervalEnd])
            ->whereNotNull('application')
            ->select('application')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('SUM(packets) as total_packets')
            ->selectRaw('COUNT(*) as flow_count')
            ->groupBy('application')
            ->get();

        foreach ($appStats as $stat) {
            TrafficStatistic::create([
                'device_id' => $device->id,
                'application' => $stat->application,
                'total_bytes' => $stat->total_bytes,
                'total_packets' => $stat->total_packets,
                'flow_count' => $stat->flow_count,
                'interval_type' => $intervalType,
                'interval_start' => $intervalStart,
                'interval_end' => $intervalEnd,
            ]);
        }
    }

    public function getTopApplications(Device $device, int $limit = 10, string $timeRange = '1hour'): array
    {
        $start = $this->getTimeRangeStart($timeRange);
        
        return TrafficStatistic::where('device_id', $device->id)
            ->whereNotNull('application')
            ->where('interval_start', '>=', $start)
            ->select('application')
            ->selectRaw('SUM(total_bytes) as bytes')
            ->groupBy('application')
            ->orderByDesc('bytes')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getTopProtocols(Device $device, int $limit = 10, string $timeRange = '1hour'): array
    {
        $start = $this->getTimeRangeStart($timeRange);
        
        return TrafficStatistic::where('device_id', $device->id)
            ->whereNotNull('protocol')
            ->where('interval_start', '>=', $start)
            ->select('protocol')
            ->selectRaw('SUM(total_bytes) as bytes')
            ->groupBy('protocol')
            ->orderByDesc('bytes')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getTrafficTrend(Device $device, string $timeRange = '1hour'): array
    {
        $start = $this->getTimeRangeStart($timeRange);
        
        return TrafficStatistic::where('device_id', $device->id)
            ->where('interval_start', '>=', $start)
            ->orderBy('interval_start')
            ->select('interval_start', DB::raw('SUM(total_bytes) as bytes'))
            ->groupBy('interval_start')
            ->get()
            ->map(function ($stat) {
                return [
                    'time' => $stat->interval_start->format('H:i'),
                    'bytes' => $stat->bytes
                ];
            })
            ->toArray();
    }

    private function getTimeRangeStart(string $timeRange): Carbon
    {
        return match($timeRange) {
            '1hour' => now()->subHour(),
            '6hours' => now()->subHours(6),
            '24hours' => now()->subDay(),
            '7days' => now()->subDays(7),
            default => now()->subHour(),
        };
    }

    /**
     * Get traffic direction distribution (inbound vs outbound)
     */
    public function getTrafficDistribution(Device $device, string $timeRange = '1hour'): array
    {
        $start = $this->getTimeRangeStart($timeRange);

        // Get device's local network (assume /24 for simplicity based on device IP)
        $deviceIpParts = explode('.', $device->ip_address);
        $localNetwork = $deviceIpParts[0] . '.' . $deviceIpParts[1] . '.' . $deviceIpParts[2] . '.';

        $flows = Flow::where('device_id', $device->id)
            ->where('created_at', '>=', $start)
            ->get();

        $inboundBytes = 0;
        $outboundBytes = 0;

        foreach ($flows as $flow) {
            // Inbound: source is external, destination is internal
            // Outbound: source is internal, destination is external
            $sourceIsLocal = str_starts_with($flow->source_ip, $localNetwork) ||
                             str_starts_with($flow->source_ip, '10.') ||
                             str_starts_with($flow->source_ip, '192.168.') ||
                             str_starts_with($flow->source_ip, '172.16.');

            $destIsLocal = str_starts_with($flow->destination_ip, $localNetwork) ||
                           str_starts_with($flow->destination_ip, '10.') ||
                           str_starts_with($flow->destination_ip, '192.168.') ||
                           str_starts_with($flow->destination_ip, '172.16.');

            if (!$sourceIsLocal && $destIsLocal) {
                $inboundBytes += $flow->bytes;
            } elseif ($sourceIsLocal && !$destIsLocal) {
                $outboundBytes += $flow->bytes;
            } else {
                // Internal traffic - split evenly for display
                $inboundBytes += $flow->bytes / 2;
                $outboundBytes += $flow->bytes / 2;
            }
        }

        $totalBytes = $inboundBytes + $outboundBytes;

        return [
            'inbound_bytes' => (int) $inboundBytes,
            'outbound_bytes' => (int) $outboundBytes,
            'total_bytes' => (int) $totalBytes,
            'inbound_percent' => $totalBytes > 0 ? round(($inboundBytes / $totalBytes) * 100, 2) : 0,
            'outbound_percent' => $totalBytes > 0 ? round(($outboundBytes / $totalBytes) * 100, 2) : 0,
        ];
    }

    /**
     * Get traffic time series data for charts
     */
    public function getTrafficTimeSeries(Device $device, string $timeRange = '24hours'): array
    {
        $start = $this->getTimeRangeStart($timeRange);

        // Determine interval based on time range
        // PostgreSQL date_trunc only accepts: microseconds, milliseconds, second, minute, hour, day, week, month, quarter, year
        // For 10 minute intervals, we use a custom expression
        $truncFunction = match($timeRange) {
            '1hour' => "date_trunc('minute', created_at)",
            '6hours' => "date_trunc('hour', created_at) + INTERVAL '10 min' * FLOOR(EXTRACT(MINUTE FROM created_at) / 10)",
            '24hours' => "date_trunc('hour', created_at)",
            '7days' => "date_trunc('day', created_at)",
            default => "date_trunc('hour', created_at)",
        };

        $data = Flow::where('device_id', $device->id)
            ->where('created_at', '>=', $start)
            ->select(DB::raw("{$truncFunction} as time_bucket"))
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('SUM(packets) as total_packets')
            ->selectRaw('COUNT(*) as flow_count')
            ->groupBy('time_bucket')
            ->orderBy('time_bucket')
            ->get();

        // Format for chart display
        $labels = [];
        $bytesData = [];
        $packetsData = [];

        foreach ($data as $row) {
            $time = Carbon::parse($row->time_bucket);
            $labels[] = match($timeRange) {
                '1hour' => $time->format('H:i'),
                '6hours' => $time->format('H:i'),
                '24hours' => $time->format('H:00'),
                '7days' => $time->format('M d'),
                default => $time->format('H:i'),
            };
            $bytesData[] = (int) $row->total_bytes;
            $packetsData[] = (int) $row->total_packets;
        }

        return [
            'labels' => $labels,
            'bytes' => $bytesData,
            'packets' => $packetsData,
        ];
    }

    /**
     * Get summary statistics for a device
     */
    public function getDeviceSummary(Device $device, string $timeRange = '1hour'): array
    {
        $start = $this->getTimeRangeStart($timeRange);

        $stats = Flow::where('device_id', $device->id)
            ->where('created_at', '>=', $start)
            ->selectRaw('COUNT(*) as total_flows')
            ->selectRaw('COALESCE(SUM(bytes), 0) as total_bytes')
            ->selectRaw('COALESCE(SUM(packets), 0) as total_packets')
            ->selectRaw('COALESCE(AVG(bytes), 0) as avg_bytes')
            ->first();

        $duration = max(1, now()->diffInSeconds($start));

        return [
            'total_flows' => (int) $stats->total_flows,
            'total_bytes' => (int) $stats->total_bytes,
            'total_packets' => (int) $stats->total_packets,
            'avg_bandwidth' => (int) ($stats->total_bytes / $duration),
        ];
    }

    /**
     * Get traffic by application for a device
     */
    public function getTrafficByApplication(Device $device, string $timeRange = '1hour'): \Illuminate\Support\Collection
    {
        $start = $this->getTimeRangeStart($timeRange);

        return Flow::where('device_id', $device->id)
            ->where('created_at', '>=', $start)
            ->whereNotNull('application')
            ->select('application')
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->groupBy('application')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get();
    }

    /**
     * Get traffic by protocol for a device
     */
    public function getTrafficByProtocol(Device $device, string $timeRange = '1hour'): \Illuminate\Support\Collection
    {
        $start = $this->getTimeRangeStart($timeRange);

        return Flow::where('device_id', $device->id)
            ->where('created_at', '>=', $start)
            ->select('protocol')
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->groupBy('protocol')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get();
    }

    /**
     * Get top source IPs for a device
     */
    public function getTopSources(Device $device, string $timeRange = '1hour', int $limit = 20): \Illuminate\Support\Collection
    {
        $start = $this->getTimeRangeStart($timeRange);

        return Flow::where('device_id', $device->id)
            ->where('created_at', '>=', $start)
            ->select('source_ip')
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->groupBy('source_ip')
            ->orderByDesc('total_bytes')
            ->limit($limit)
            ->get();
    }

    /**
     * Get top destination IPs for a device
     */
    public function getTopDestinations(Device $device, string $timeRange = '1hour', int $limit = 20): \Illuminate\Support\Collection
    {
        $start = $this->getTimeRangeStart($timeRange);

        return Flow::where('device_id', $device->id)
            ->where('created_at', '>=', $start)
            ->select('destination_ip')
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->groupBy('destination_ip')
            ->orderByDesc('total_bytes')
            ->limit($limit)
            ->get();
    }

    /**
     * Get QoS/DSCP distribution for a device
     */
    public function getQosDistribution(Device $device, string $timeRange = '1hour'): \Illuminate\Support\Collection
    {
        $start = $this->getTimeRangeStart($timeRange);

        return Flow::where('device_id', $device->id)
            ->where('created_at', '>=', $start)
            ->whereNotNull('dscp')
            ->select('dscp')
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->groupBy('dscp')
            ->orderByDesc('total_bytes')
            ->get();
    }

    /**
     * Get top conversations for a device
     */
    public function getTopConversations(Device $device, string $timeRange = '1hour', int $limit = 20): \Illuminate\Support\Collection
    {
        $start = $this->getTimeRangeStart($timeRange);

        return Flow::where('device_id', $device->id)
            ->where('created_at', '>=', $start)
            ->select('source_ip', 'destination_ip', 'protocol', 'application')
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('SUM(packets) as total_packets')
            ->groupBy('source_ip', 'destination_ip', 'protocol', 'application')
            ->orderByDesc('total_bytes')
            ->limit($limit)
            ->get();
    }
}