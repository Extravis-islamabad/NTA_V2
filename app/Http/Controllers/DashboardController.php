<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Alarm;
use App\Models\Flow;
use App\Services\ApplicationIdentificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected ApplicationIdentificationService $appService;

    public function __construct(ApplicationIdentificationService $appService)
    {
        $this->appService = $appService;
    }

    public function index(Request $request)
    {
        $timeRange = $request->get('range', '1hour');
        $timeStart = $this->getTimeRangeStart($timeRange);
        $stats = [
            'total_devices' => Device::count(),
            'online_devices' => Device::where('status', 'online')->count(),
            'offline_devices' => Device::where('status', 'offline')->count(),
            'total_flows' => Device::sum('flow_count'),
            'active_alarms' => Alarm::where('status', 'active')->count(),
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

        return view('dashboard', compact(
            'stats',
            'devices',
            'recentAlarms',
            'heatMapData',
            'topQoS',
            'topConversations',
            'topApplications',
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