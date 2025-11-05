<?php

namespace App\Http\Controllers;

use App\Models\Alarm;
use App\Models\Device;
use Illuminate\Http\Request;

class AlarmController extends Controller
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
        
        $stats = [
            'total' => Alarm::count(),
            'active' => Alarm::where('status', 'active')->count(),
            'critical' => Alarm::where('severity', 'critical')->where('status', 'active')->count(),
            'warning' => Alarm::where('severity', 'warning')->where('status', 'active')->count(),
        ];

        return view('alarms.index', compact('alarms', 'stats'));
    }

    public function show(Alarm $alarm)
    {
        $alarm->load('device');
        return view('alarms.show', compact('alarm'));
    }

    public function acknowledge(Alarm $alarm)
    {
        $alarm->acknowledge();
        return redirect()->back()->with('success', 'Alarm acknowledged successfully');
    }

    public function resolve(Alarm $alarm)
    {
        $alarm->resolve();
        return redirect()->back()->with('success', 'Alarm resolved successfully');
    }
}