<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RealTimeBandwidthService;
use App\Models\Device;
use App\Models\DeviceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RealtimeApiController extends Controller
{
    public function __construct(
        private RealTimeBandwidthService $bandwidthService
    ) {}

    /**
     * Get current bandwidth for a device
     */
    public function deviceBandwidth(Device $device): JsonResponse
    {
        $stats = $this->bandwidthService->calculateDeviceBandwidth($device);

        return response()->json([
            'success' => true,
            'device' => [
                'id' => $device->id,
                'name' => $device->name,
                'ip_address' => $device->ip_address,
                'status' => $device->status,
            ],
            'bandwidth' => $stats,
        ]);
    }

    /**
     * Get sparkline data for a device
     */
    public function sparklineData(Request $request, Device $device): JsonResponse
    {
        $points = min($request->input('points', 20), 100);
        $minutes = min($request->input('minutes', 30), 1440);

        $data = $this->bandwidthService->getSparklineData($device, $points, $minutes);

        return response()->json([
            'success' => true,
            'device_id' => $device->id,
            'data' => $data,
        ]);
    }

    /**
     * Get interface bandwidth for a device
     */
    public function interfaceBandwidth(Device $device): JsonResponse
    {
        $interfaces = $device->interfaces()->get()->map(function ($interface) {
            $stats = $this->bandwidthService->calculateInterfaceBandwidth($interface);
            return [
                'id' => $interface->id,
                'name' => $interface->name,
                'if_index' => $interface->if_index,
                'status' => $interface->status,
                'speed' => $interface->speed,
                'bandwidth' => $stats,
            ];
        });

        return response()->json([
            'success' => true,
            'device_id' => $device->id,
            'interfaces' => $interfaces,
        ]);
    }

    /**
     * Get all devices bandwidth summary
     */
    public function allDevicesBandwidth(): JsonResponse
    {
        $devices = $this->bandwidthService->getAllDevicesBandwidth();

        return response()->json([
            'success' => true,
            'devices' => $devices,
        ]);
    }

    /**
     * Get dashboard summary
     */
    public function dashboardSummary(Request $request): JsonResponse
    {
        $timeRange = $request->input('range', '1hour');
        $summary = $this->bandwidthService->getDashboardSummary($timeRange);

        return response()->json([
            'success' => true,
            'data' => $summary,
        ]);
    }

    /**
     * Get top talkers
     */
    public function topTalkers(Request $request): JsonResponse
    {
        $timeRange = $request->input('range', '1hour');
        $limit = min($request->input('limit', 10), 50);

        $talkers = $this->bandwidthService->getTopTalkers($timeRange, $limit);

        return response()->json([
            'success' => true,
            'data' => $talkers,
        ]);
    }
}
