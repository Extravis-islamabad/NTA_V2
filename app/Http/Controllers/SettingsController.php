<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = $this->getSettings();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            // NetFlow Collector Configuration
            'collector_ip' => 'nullable|ip',
            'netflow_port' => 'required|integer|min:1024|max:65535',
            'sflow_port' => 'nullable|integer|min:1024|max:65535',
            'ipfix_port' => 'nullable|integer|min:1024|max:65535',

            // NetFlow Versions
            'netflow_v5' => 'nullable',
            'netflow_v9' => 'nullable',
            'ipfix' => 'nullable',
            'sflow' => 'nullable',

            // Sample Rate & Timeout
            'sample_rate' => 'nullable|integer|min:1',
            'active_timeout' => 'nullable|integer|min:30|max:600',

            // Data Management
            'retention_days' => 'required|integer|min:1|max:365',
            'aggregation_interval' => 'nullable|string|in:1min,5min,10min,15min,1hour',
            'aggregation_enabled' => 'nullable',
            'dns_resolution' => 'nullable',
            'geolocation_enabled' => 'nullable',
            'as_lookup_enabled' => 'nullable',

            // Alert Thresholds
            'traffic_threshold' => 'nullable|integer|min:1',
            'offline_timeout' => 'nullable|integer|min:1|max:60',
            'utilization_warning' => 'nullable|integer|min:1|max:100',
            'utilization_critical' => 'nullable|integer|min:1|max:100',
        ]);

        // Process boolean checkboxes
        $settings = [
            'collector_ip' => $validated['collector_ip'] ?? '192.168.10.7',
            'netflow_port' => $validated['netflow_port'] ?? 2055,
            'sflow_port' => $validated['sflow_port'] ?? 6343,
            'ipfix_port' => $validated['ipfix_port'] ?? 4739,

            'netflow_v5' => $request->has('netflow_v5'),
            'netflow_v9' => $request->has('netflow_v9'),
            'ipfix' => $request->has('ipfix'),
            'sflow' => $request->has('sflow'),

            'sample_rate' => $validated['sample_rate'] ?? 1,
            'active_timeout' => $validated['active_timeout'] ?? 60,

            'retention_days' => $validated['retention_days'] ?? 7,
            'aggregation_interval' => $validated['aggregation_interval'] ?? '1min',
            'aggregation_enabled' => $request->has('aggregation_enabled'),
            'dns_resolution' => $request->has('dns_resolution'),
            'geolocation_enabled' => $request->has('geolocation_enabled'),
            'as_lookup_enabled' => $request->has('as_lookup_enabled'),

            'traffic_threshold' => $validated['traffic_threshold'] ?? 1000,
            'offline_timeout' => $validated['offline_timeout'] ?? 5,
            'utilization_warning' => $validated['utilization_warning'] ?? 80,
            'utilization_critical' => $validated['utilization_critical'] ?? 95,
        ];

        Cache::forever('netflow_settings', $settings);

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    private function getSettings(): array
    {
        $cached = Cache::get('netflow_settings', []);

        return array_merge([
            'collector_ip' => config('netflow.collector_ip', '192.168.10.7'),
            'netflow_port' => config('netflow.port', 2055),
            'sflow_port' => 6343,
            'ipfix_port' => 4739,

            'netflow_v5' => true,
            'netflow_v9' => true,
            'ipfix' => true,
            'sflow' => false,

            'sample_rate' => 1,
            'active_timeout' => 60,

            'retention_days' => config('netflow.retention_days', 7),
            'aggregation_interval' => '1min',
            'aggregation_enabled' => true,
            'dns_resolution' => false,
            'geolocation_enabled' => true,
            'as_lookup_enabled' => true,

            'traffic_threshold' => 1000,
            'offline_timeout' => 5,
            'utilization_warning' => 80,
            'utilization_critical' => 95,
        ], $cached);
    }
}
