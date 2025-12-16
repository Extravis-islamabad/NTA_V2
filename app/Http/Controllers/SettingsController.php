<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::getAllSettings();
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

        // Save settings to database
        Setting::set('collector_ip', $validated['collector_ip'] ?? '', 'string');
        Setting::set('netflow_port', $validated['netflow_port'] ?? '', 'integer');
        Setting::set('sflow_port', $validated['sflow_port'] ?? '', 'integer');
        Setting::set('ipfix_port', $validated['ipfix_port'] ?? '', 'integer');

        Setting::set('netflow_v5', $request->has('netflow_v5') ? 'true' : 'false', 'boolean');
        Setting::set('netflow_v9', $request->has('netflow_v9') ? 'true' : 'false', 'boolean');
        Setting::set('ipfix', $request->has('ipfix') ? 'true' : 'false', 'boolean');
        Setting::set('sflow', $request->has('sflow') ? 'true' : 'false', 'boolean');

        Setting::set('sample_rate', $validated['sample_rate'] ?? 1, 'integer');
        Setting::set('active_timeout', $validated['active_timeout'] ?? 60, 'integer');

        Setting::set('retention_days', $validated['retention_days'] ?? 7, 'integer');
        Setting::set('aggregation_interval', $validated['aggregation_interval'] ?? '1min', 'string');
        Setting::set('aggregation_enabled', $request->has('aggregation_enabled') ? 'true' : 'false', 'boolean');
        Setting::set('dns_resolution', $request->has('dns_resolution') ? 'true' : 'false', 'boolean');
        Setting::set('geolocation_enabled', $request->has('geolocation_enabled') ? 'true' : 'false', 'boolean');
        Setting::set('as_lookup_enabled', $request->has('as_lookup_enabled') ? 'true' : 'false', 'boolean');

        Setting::set('traffic_threshold', $validated['traffic_threshold'] ?? 1000, 'integer');
        Setting::set('offline_timeout', $validated['offline_timeout'] ?? 5, 'integer');
        Setting::set('utilization_warning', $validated['utilization_warning'] ?? 80, 'integer');
        Setting::set('utilization_critical', $validated['utilization_critical'] ?? 95, 'integer');

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
