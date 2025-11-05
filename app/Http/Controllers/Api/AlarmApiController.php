<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alarm;
use Illuminate\Http\Request;

class AlarmApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Alarm::with('device')->latest();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('severity')) {
            $query->where('severity', $request->severity);
        }

        $alarms = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $alarms
        ]);
    }

    public function show(Alarm $alarm)
    {
        $alarm->load('device');

        return response()->json([
            'success' => true,
            'data' => $alarm
        ]);
    }

    public function acknowledge(Alarm $alarm)
    {
        $alarm->acknowledge();

        return response()->json([
            'success' => true,
            'message' => 'Alarm acknowledged',
            'data' => $alarm
        ]);
    }

    public function resolve(Alarm $alarm)
    {
        $alarm->resolve();

        return response()->json([
            'success' => true,
            'message' => 'Alarm resolved',
            'data' => $alarm
        ]);
    }

    public function stats()
    {
        $stats = [
            'total' => Alarm::count(),
            'active' => Alarm::where('status', 'active')->count(),
            'acknowledged' => Alarm::where('status', 'acknowledged')->count(),
            'resolved' => Alarm::where('status', 'resolved')->count(),
            'critical' => Alarm::where('severity', 'critical')->where('status', 'active')->count(),
            'warning' => Alarm::where('severity', 'warning')->where('status', 'active')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}