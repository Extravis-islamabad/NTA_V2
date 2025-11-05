<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flow;
use App\Models\Device;
use Illuminate\Http\Request;

class FlowApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Flow::with('device')->latest();

        if ($request->has('device_id')) {
            $query->where('device_id', $request->device_id);
        }

        if ($request->has('protocol')) {
            $query->where('protocol', $request->protocol);
        }

        if ($request->has('application')) {
            $query->where('application', $request->application);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $flows = $query->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $flows
        ]);
    }

    public function byDevice(Device $device, Request $request)
    {
        $query = $device->flows()->latest();

        if ($request->has('limit')) {
            $query->limit($request->limit);
        }

        $flows = $query->get();

        return response()->json([
            'success' => true,
            'data' => $flows
        ]);
    }

    public function statistics(Request $request)
    {
        $deviceId = $request->get('device_id');
        $timeRange = $request->get('range', '1hour');

        $start = match($timeRange) {
            '1hour' => now()->subHour(),
            '6hours' => now()->subHours(6),
            '24hours' => now()->subDay(),
            '7days' => now()->subDays(7),
            default => now()->subHour(),
        };

        $query = Flow::where('created_at', '>=', $start);

        if ($deviceId) {
            $query->where('device_id', $deviceId);
        }

        $stats = [
            'total_flows' => $query->count(),
            'total_bytes' => $query->sum('bytes'),
            'total_packets' => $query->sum('packets'),
            'protocols' => $query->select('protocol')
                ->selectRaw('COUNT(*) as count, SUM(bytes) as bytes')
                ->groupBy('protocol')
                ->get(),
            'applications' => $query->whereNotNull('application')
                ->select('application')
                ->selectRaw('COUNT(*) as count, SUM(bytes) as bytes')
                ->groupBy('application')
                ->orderByDesc('bytes')
                ->limit(10)
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}