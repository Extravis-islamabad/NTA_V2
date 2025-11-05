<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Alarm;
use App\Models\Flow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
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
        $topQoS = Flow::where('created_at', '>=', now()->subHour())
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

        // Top Conversations
        $topConversations = Flow::where('created_at', '>=', now()->subHour())
            ->select('source_ip', 'destination_ip', 'application', 'dscp')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->groupBy('source_ip', 'destination_ip', 'application', 'dscp')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'source' => $item->source_ip,
                    'destination' => $item->destination_ip,
                    'application' => $item->application ?? 'Unknown',
                    'dscp' => $item->dscp ? str_pad($item->dscp, 6, '0', STR_PAD_LEFT) : '000000',
                    'bytes' => $item->total_bytes,
                    'formatted_bytes' => $this->formatBytes($item->total_bytes)
                ];
            });

        return view('dashboard', compact(
            'stats', 
            'devices', 
            'recentAlarms',
            'heatMapData',
            'topQoS',
            'topConversations'
        ));
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