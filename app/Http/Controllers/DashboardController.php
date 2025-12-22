<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Alarm;
use App\Models\Flow;
use App\Models\BandwidthSample;
use App\Services\ApplicationIdentificationService;
use App\Services\RealTimeBandwidthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected ApplicationIdentificationService $appService;
    protected RealTimeBandwidthService $bandwidthService;

    public function __construct(
        ApplicationIdentificationService $appService,
        RealTimeBandwidthService $bandwidthService
    ) {
        $this->appService = $appService;
        $this->bandwidthService = $bandwidthService;
    }

    public function index(Request $request)
    {
        $timeRange = $request->get('range', '1hour');
        $timeStart = $this->getTimeRangeStart($timeRange);

        // Calculate total bandwidth
        $totalBandwidth = $this->bandwidthService->getDashboardSummary($timeRange);

        $stats = [
            'total_devices' => Device::count(),
            'online_devices' => Device::where('status', 'online')->count(),
            'offline_devices' => Device::where('status', 'offline')->count(),
            'total_flows' => Flow::where('created_at', '>=', $timeStart)->count(),
            'active_alarms' => Alarm::where('status', 'active')->count(),
            'total_bandwidth' => $totalBandwidth['total_bandwidth'] ?? '0 B',
            'total_bandwidth_in' => $totalBandwidth['total_in'] ?? '0 B',
            'total_bandwidth_out' => $totalBandwidth['total_out'] ?? '0 B',
        ];

        $devices = Device::with('interfaces')->get();
        
        $recentAlarms = Alarm::with('device')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // HeatMap Data - Device Status Distribution
        $heatMapData = [
            'link_up' => Device::where('status', 'online')->count(),
            'link_down' => Device::where('status', 'offline')->count(),
            'unknown' => Device::where('status', 'warning')->count(),
        ];

        // Top QoS Data
        $topQoS = Flow::where('created_at', '>=', $timeStart)
            ->whereNotNull('dscp')
            ->select('dscp')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->groupBy('dscp')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'dscp' => 'AF' . $item->dscp,
                    'bytes' => $item->total_bytes,
                    'formatted_bytes' => $this->formatBytes($item->total_bytes)
                ];
            });

        // Top Conversations with Application Identification
        $topConversations = Flow::where('created_at', '>=', $timeStart)
            ->select('source_ip', 'destination_ip', 'destination_port', 'application', 'dscp')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->groupBy('source_ip', 'destination_ip', 'destination_port', 'application', 'dscp')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                // Use ApplicationIdentificationService to identify the app
                $appInfo = $this->appService->analyzeFlow(
                    $item->source_ip,
                    $item->destination_ip,
                    $item->destination_port ?? 0,
                    $item->application
                );

                return [
                    'source' => $item->source_ip,
                    'destination' => $item->destination_ip,
                    'application' => $appInfo['name'],
                    'app_icon' => $appInfo['icon'],
                    'app_color' => $appInfo['color'],
                    'app_category' => $appInfo['category'],
                    'dscp' => $item->dscp ? str_pad($item->dscp, 6, '0', STR_PAD_LEFT) : '000000',
                    'bytes' => $item->total_bytes,
                    'formatted_bytes' => $this->formatBytes($item->total_bytes)
                ];
            });

        // Top Applications with enhanced identification
        $topApplications = Flow::where('created_at', '>=', $timeStart)
            ->select('source_ip', 'destination_ip', 'destination_port', 'application')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->groupBy('source_ip', 'destination_ip', 'destination_port', 'application')
            ->orderByDesc('total_bytes')
            ->limit(100)
            ->get()
            ->map(function ($item) {
                $appInfo = $this->appService->analyzeFlow(
                    $item->source_ip,
                    $item->destination_ip,
                    $item->destination_port ?? 0,
                    $item->application
                );
                return [
                    'name' => $appInfo['name'],
                    'icon' => $appInfo['icon'],
                    'color' => $appInfo['color'],
                    'category' => $appInfo['category'],
                    'bytes' => $item->total_bytes,
                ];
            })
            ->groupBy('name')
            ->map(function ($group, $name) {
                $first = $group->first();
                return [
                    'name' => $name,
                    'icon' => $first['icon'],
                    'color' => $first['color'],
                    'category' => $first['category'],
                    'bytes' => $group->sum('bytes'),
                    'formatted_bytes' => $this->formatBytes($group->sum('bytes')),
                ];
            })
            ->sortByDesc('bytes')
            ->take(10)
            ->values();

        // Protocol Distribution (for dashboard chart - no API call needed)
        $topProtocols = Flow::where('created_at', '>=', $timeStart)
            ->select('protocol')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('COUNT(*) as flow_count')
            ->groupBy('protocol')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'protocol' => $item->protocol ?? 'Unknown',
                    'bytes' => $item->total_bytes,
                    'flows' => $item->flow_count,
                    'formatted_bytes' => $this->formatBytes($item->total_bytes)
                ];
            });

        // Traffic by Country for World Map
        $trafficByCountry = Flow::where('created_at', '>=', $timeStart)
            ->whereNotNull('dst_country_code')
            ->select('dst_country_code', 'dst_country_name', 'dst_latitude', 'dst_longitude')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('COUNT(*) as flow_count')
            ->groupBy('dst_country_code', 'dst_country_name', 'dst_latitude', 'dst_longitude')
            ->orderByDesc('total_bytes')
            ->limit(20)
            ->get()
            ->map(function ($item) {
                return [
                    'country_code' => $item->dst_country_code,
                    'country_name' => $item->dst_country_name ?? 'Unknown',
                    'latitude' => $item->dst_latitude ?? 0,
                    'longitude' => $item->dst_longitude ?? 0,
                    'bytes' => $item->total_bytes,
                    'flows' => $item->flow_count,
                    'formatted_bytes' => $this->formatBytes($item->total_bytes),
                ];
            });

        // Top Source IPs with geolocation
        $topSources = Flow::where('created_at', '>=', $timeStart)
            ->select('source_ip', 'src_country_code', 'src_country_name', 'src_city')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('COUNT(*) as flow_count')
            ->groupBy('source_ip', 'src_country_code', 'src_country_name', 'src_city')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'ip' => $item->source_ip,
                    'country_code' => $item->src_country_code,
                    'country_name' => $item->src_country_name ?? 'Unknown',
                    'city' => $item->src_city ?? '',
                    'bytes' => $item->total_bytes,
                    'flows' => $item->flow_count,
                    'formatted_bytes' => $this->formatBytes($item->total_bytes),
                ];
            });

        // Top Destination IPs with geolocation
        $topDestinations = Flow::where('created_at', '>=', $timeStart)
            ->select('destination_ip', 'dst_country_code', 'dst_country_name', 'dst_city')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('COUNT(*) as flow_count')
            ->groupBy('destination_ip', 'dst_country_code', 'dst_country_name', 'dst_city')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'ip' => $item->destination_ip,
                    'country_code' => $item->dst_country_code,
                    'country_name' => $item->dst_country_name ?? 'Unknown',
                    'city' => $item->dst_city ?? '',
                    'bytes' => $item->total_bytes,
                    'flows' => $item->flow_count,
                    'formatted_bytes' => $this->formatBytes($item->total_bytes),
                ];
            });

        // Device bandwidth data for sparklines
        $deviceBandwidth = Device::where('status', 'online')
            ->get()
            ->map(function ($device) {
                $bandwidth = $this->bandwidthService->calculateDeviceBandwidth($device);
                $sparkline = $this->bandwidthService->getSparklineData($device, 20, 30);
                return [
                    'id' => $device->id,
                    'name' => $device->name,
                    'ip_address' => $device->ip_address,
                    'status' => $device->status,
                    'bandwidth' => $bandwidth,
                    'sparkline' => $sparkline,
                ];
            });

        return view('dashboard', compact(
            'stats',
            'devices',
            'recentAlarms',
            'heatMapData',
            'topQoS',
            'topProtocols',
            'topConversations',
            'topApplications',
            'trafficByCountry',
            'topSources',
            'topDestinations',
            'deviceBandwidth',
            'timeRange'
        ));
    }

    private function getTimeRangeStart(string $timeRange)
    {
        return match($timeRange) {
            '1hour' => now()->subHour(),
            '6hours' => now()->subHours(6),
            '24hours' => now()->subDay(),
            '7days' => now()->subDays(7),
            default => now()->subHour(),
        };
    }

    private function formatBytes($bytes)
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
}