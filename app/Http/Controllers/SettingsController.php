<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'netflow_port' => config('netflow.port', 9996),
            'retention_days' => config('netflow.retention_days', 7),
            'aggregation_enabled' => config('netflow.aggregation_enabled', true),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'netflow_port' => 'required|integer|min:1024|max:65535',
            'retention_days' => 'required|integer|min:1|max:365',
            'aggregation_enabled' => 'boolean',
        ]);

        // In production, save to database or config file
        Cache::forever('settings', $validated);

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully');
    }
}