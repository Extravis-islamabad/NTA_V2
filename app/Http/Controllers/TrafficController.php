<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Flow;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TrafficController extends Controller
{
    public function index(Request $request)
    {
        $devices = Device::all();
        $selectedDevice = null;
        $timeRange = $request->get('range', '1hour');
        
        if ($request->has('device_id')) {
            $selectedDevice = Device::find($request->device_id);
        }

        return view('traffic.index', compact('devices', 'selectedDevice', 'timeRange'));
    }

    public function show(Device $device, Request $request)
    {
        $timeRange = $request->get('range', '1hour');
        $start = $this->getTimeRangeStart($timeRange);

        $flows = $device->flows()
            ->where('created_at', '>=', $start)
            ->with('device')
            ->latest()
            ->paginate(50);

        $topSources = $device->flows()
            ->where('created_at', '>=', $start)
            ->selectRaw('source_ip, SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('source_ip')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get();

        $topDestinations = $device->flows()
            ->where('created_at', '>=', $start)
            ->selectRaw('destination_ip, SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('destination_ip')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get();

        return view('traffic.show', compact('device', 'flows', 'topSources', 'topDestinations', 'timeRange'));
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