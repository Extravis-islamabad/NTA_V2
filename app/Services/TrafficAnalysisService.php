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
}