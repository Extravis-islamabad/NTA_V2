<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Services\TrafficAnalysisService;
use Illuminate\Http\Request;

class DeviceApiController extends Controller
{
    protected TrafficAnalysisService $trafficService;

    public function __construct(TrafficAnalysisService $trafficService)
    {
        $this->trafficService = $trafficService;
    }

    public function index()
    {
        $devices = Device::with('interfaces')
            ->withCount('flows')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $devices
        ]);
    }

    public function show(Device $device)
    {
        $device->load(['interfaces', 'alarms']);

        return response()->json([
            'success' => true,
            'data' => $device
        ]);
    }

    public function traffic(Device $device, Request $request)
    {
        $timeRange = $request->get('range', '1hour');

        $data = [
            'device' => $device,
            'top_applications' => $this->trafficService->getTopApplications($device, 10, $timeRange),
            'top_protocols' => $this->trafficService->getTopProtocols($device, 10, $timeRange),
            'traffic_trend' => $this->trafficService->getTrafficTrend($device, $timeRange),
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function interfaces(Device $device)
    {
        $interfaces = $device->interfaces;

        return response()->json([
            'success' => true,
            'data' => $interfaces
        ]);
    }
}