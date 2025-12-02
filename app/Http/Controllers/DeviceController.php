<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceInterface;
use App\Services\TrafficAnalysisService;
use App\Services\CloudProviderService;
use App\Services\ASLookupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{
    protected TrafficAnalysisService $trafficService;
    protected CloudProviderService $cloudService;
    protected ASLookupService $asService;

    public function __construct(
        TrafficAnalysisService $trafficService,
        CloudProviderService $cloudService,
        ASLookupService $asService
    ) {
        $this->trafficService = $trafficService;
        $this->cloudService = $cloudService;
        $this->asService = $asService;
    }

    public function index()
    {
        $devices = Device::with('interfaces')->get();
        return view('devices.index', compact('devices'));
    }

    public function show(Device $device, Request $request)
    {
        $tab = $request->get('tab', 'summary');
        $timeRange = $request->get('range', '1hour');
        
        $device->load(['interfaces', 'flows' => function($query) use ($timeRange) {
            $start = $this->getTimeRangeStart($timeRange);
            $query->where('created_at', '>=', $start)->latest()->limit(100);
        }]);

        // Summary Data
        $summaryData = $this->trafficService->getDeviceSummary($device, $timeRange);

        // Traffic Distribution (inbound vs outbound)
        $trafficDistribution = $this->trafficService->getTrafficDistribution($device, $timeRange);

        // Traffic Time Series for charts
        $trafficTimeSeries = $this->trafficService->getTrafficTimeSeries($device, $timeRange);

        // Flow Details
        $flowDetails = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->latest()
            ->paginate(50);

        // Traffic by Application
        $trafficByApp = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->whereNotNull('application')
            ->select('application')
            ->selectRaw('SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('application')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get();

        // Traffic by Protocol
        $trafficByProtocol = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->select('protocol')
            ->selectRaw('SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('protocol')
            ->orderByDesc('total_bytes')
            ->get();

        // Source IPs
        $topSources = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->select('source_ip')
            ->selectRaw('SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('source_ip')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get();

        // Destination IPs
        $topDestinations = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->select('destination_ip')
            ->selectRaw('SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('destination_ip')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get();

        // QoS Data
        $qosData = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->whereNotNull('dscp')
            ->select('dscp')
            ->selectRaw('SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('dscp')
            ->orderByDesc('total_bytes')
            ->get();

        // Conversations
        $conversations = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->select('source_ip', 'destination_ip', 'protocol', 'application')
            ->selectRaw('SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->groupBy('source_ip', 'destination_ip', 'protocol', 'application')
            ->orderByDesc('total_bytes')
            ->limit(20)
            ->get();

        // Cloud Services Data
        $cloudTraffic = [];
        $allFlows = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->get();

        foreach ($allFlows as $flow) {
            // Check destination IP for cloud providers
            $cloudProvider = $this->cloudService->identifyProvider($flow->destination_ip);
            if ($cloudProvider) {
                $key = $cloudProvider['provider'];
                if (!isset($cloudTraffic[$key])) {
                    $cloudTraffic[$key] = [
                        'provider' => $cloudProvider['name'],
                        'bytes' => 0,
                        'flows' => 0,
                        'ips' => []
                    ];
                }
                $cloudTraffic[$key]['bytes'] += $flow->bytes;
                $cloudTraffic[$key]['flows']++;
                $cloudTraffic[$key]['ips'][$flow->destination_ip] = true;
            }
        }

        // Convert to collection and add unique IP count
        $cloudTraffic = collect($cloudTraffic)->map(function($item) {
            $item['unique_ips'] = count($item['ips']);
            unset($item['ips']);
            return $item;
        })->sortByDesc('bytes')->values();

        // AS View Data
        $asTraffic = [];
        foreach ($allFlows as $flow) {
            // Lookup AS for source and destination
            $sourceAS = $this->asService->lookupAS($flow->source_ip);
            $destAS = $this->asService->lookupAS($flow->destination_ip);

            // Track source AS
            if ($sourceAS) {
                $sourceKey = $sourceAS['asn'];
                if (!isset($asTraffic[$sourceKey])) {
                    $asTraffic[$sourceKey] = [
                        'asn' => $sourceAS['asn'],
                        'name' => $sourceAS['name'],
                        'country' => $sourceAS['country'],
                        'bytes_sent' => 0,
                        'bytes_received' => 0,
                        'flows' => 0
                    ];
                }
                $asTraffic[$sourceKey]['bytes_sent'] += $flow->bytes;
                $asTraffic[$sourceKey]['flows']++;
            }

            // Track destination AS
            if ($destAS) {
                $destKey = $destAS['asn'];
                if (!isset($asTraffic[$destKey])) {
                    $asTraffic[$destKey] = [
                        'asn' => $destAS['asn'],
                        'name' => $destAS['name'],
                        'country' => $destAS['country'],
                        'bytes_sent' => 0,
                        'bytes_received' => 0,
                        'flows' => 0
                    ];
                }
                $asTraffic[$destKey]['bytes_received'] += $flow->bytes;
            }
        }

        // Convert to collection and calculate totals
        $asTraffic = collect($asTraffic)
            ->map(function($item) {
                $item['total_bytes'] = $item['bytes_sent'] + $item['bytes_received'];
                return $item;
            })
            ->sortByDesc('total_bytes')
            ->values();

        return view('devices.show', compact(
            'device',
            'tab',
            'timeRange',
            'summaryData',
            'trafficDistribution',
            'trafficTimeSeries',
            'flowDetails',
            'trafficByApp',
            'trafficByProtocol',
            'topSources',
            'topDestinations',
            'qosData',
            'conversations',
            'cloudTraffic',
            'asTraffic'
        ));
    }

    public function create()
{
    return view('devices.create');
}
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'ip_address' => 'required|ip|unique:devices',
        'type' => 'required|in:router,switch,firewall,wireless_controller,checkpoint,palo_alto,fortigate,cisco_router',
        'location' => 'nullable|string|max:255',
        'device_group' => 'nullable|string|max:255',
    ]);

    $validated['status'] = 'offline';

    $device = Device::create($validated);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Device created successfully',
            'data' => $device
        ], 201);
    }

    return redirect()->route('devices.index')
        ->with('success', 'Device added successfully! Configure NetFlow/sFlow on the device to start receiving data.');
}

    public function update(Request $request, Device $device)
{
    $validated = $request->validate([
        'name' => 'sometimes|string|max:255',
        'ip_address' => 'sometimes|ip|unique:devices,ip_address,' . $device->id,
        'type' => 'sometimes|in:router,switch,firewall,wireless_controller,checkpoint,palo_alto,fortigate,cisco_router',
        'location' => 'nullable|string|max:255',
        'device_group' => 'nullable|string|max:255',
        'status' => 'sometimes|in:online,offline,warning',
    ]);

    $device->update($validated);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Device updated successfully',
            'data' => $device
        ]);
    }

    return redirect()->route('devices.index')
        ->with('success', 'Device updated successfully!');
}

    public function destroy(Device $device)
    {
        $device->delete();

        return response()->json([
            'success' => true,
            'message' => 'Device deleted successfully'
        ]);
    }

    private function getTimeRangeStart(string $timeRange)
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