<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flow;
use App\Models\Device;
use App\Services\TrafficAnalysisService;
use App\Services\CloudProviderService;
use App\Services\ASLookupService;
use Illuminate\Http\Request;

class FlowApiController extends Controller
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

        $start = $this->getTimeRangeStart($timeRange);

        $query = Flow::where('created_at', '>=', $start);

        if ($deviceId) {
            $query->where('device_id', $deviceId);
        }

        $stats = [
            'total_flows' => (clone $query)->count(),
            'total_bytes' => (clone $query)->sum('bytes'),
            'total_packets' => (clone $query)->sum('packets'),
            'protocols' => (clone $query)->select('protocol')
                ->selectRaw('COUNT(*) as count, SUM(bytes) as bytes')
                ->groupBy('protocol')
                ->get(),
            'applications' => (clone $query)->whereNotNull('application')
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

    /**
     * Get traffic distribution (inbound vs outbound)
     */
    public function trafficDistribution(Device $device, Request $request)
    {
        $timeRange = $request->get('range', '1hour');
        $distribution = $this->trafficService->getTrafficDistribution($device, $timeRange);

        return response()->json([
            'success' => true,
            'data' => $distribution
        ]);
    }

    /**
     * Get traffic time series data
     */
    public function trafficTimeSeries(Device $device, Request $request)
    {
        $timeRange = $request->get('range', '24hours');
        $timeSeries = $this->trafficService->getTrafficTimeSeries($device, $timeRange);

        return response()->json([
            'success' => true,
            'data' => $timeSeries
        ]);
    }

    /**
     * Get device summary statistics
     */
    public function deviceSummary(Device $device, Request $request)
    {
        $timeRange = $request->get('range', '1hour');
        $summary = $this->trafficService->getDeviceSummary($device, $timeRange);

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Get traffic by application
     */
    public function trafficByApplication(Device $device, Request $request)
    {
        $timeRange = $request->get('range', '1hour');
        $data = $this->trafficService->getTrafficByApplication($device, $timeRange);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get traffic by protocol
     */
    public function trafficByProtocol(Device $device, Request $request)
    {
        $timeRange = $request->get('range', '1hour');
        $data = $this->trafficService->getTrafficByProtocol($device, $timeRange);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get top source IPs
     */
    public function topSources(Device $device, Request $request)
    {
        $timeRange = $request->get('range', '1hour');
        $limit = $request->get('limit', 20);
        $data = $this->trafficService->getTopSources($device, $timeRange, $limit);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get top destination IPs
     */
    public function topDestinations(Device $device, Request $request)
    {
        $timeRange = $request->get('range', '1hour');
        $limit = $request->get('limit', 20);
        $data = $this->trafficService->getTopDestinations($device, $timeRange, $limit);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get QoS/DSCP distribution
     */
    public function qosDistribution(Device $device, Request $request)
    {
        $timeRange = $request->get('range', '1hour');
        $data = $this->trafficService->getQosDistribution($device, $timeRange);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get top conversations
     */
    public function topConversations(Device $device, Request $request)
    {
        $timeRange = $request->get('range', '1hour');
        $limit = $request->get('limit', 20);
        $data = $this->trafficService->getTopConversations($device, $timeRange, $limit);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get cloud provider traffic
     */
    public function cloudTraffic(Device $device, Request $request)
    {
        $timeRange = $request->get('range', '1hour');
        $start = $this->getTimeRangeStart($timeRange);

        $cloudTraffic = [];
        $flows = $device->flows()
            ->where('created_at', '>=', $start)
            ->get();

        foreach ($flows as $flow) {
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

        $cloudTraffic = collect($cloudTraffic)->map(function($item) {
            $item['unique_ips'] = count($item['ips']);
            unset($item['ips']);
            return $item;
        })->sortByDesc('bytes')->values();

        return response()->json([
            'success' => true,
            'data' => $cloudTraffic
        ]);
    }

    /**
     * Get AS (Autonomous System) traffic
     */
    public function asTraffic(Device $device, Request $request)
    {
        $timeRange = $request->get('range', '1hour');
        $start = $this->getTimeRangeStart($timeRange);

        $asTraffic = [];
        $flows = $device->flows()
            ->where('created_at', '>=', $start)
            ->get();

        foreach ($flows as $flow) {
            $sourceAS = $this->asService->lookupAS($flow->source_ip);
            $destAS = $this->asService->lookupAS($flow->destination_ip);

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

        $asTraffic = collect($asTraffic)
            ->map(function($item) {
                $item['total_bytes'] = $item['bytes_sent'] + $item['bytes_received'];
                return $item;
            })
            ->sortByDesc('total_bytes')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $asTraffic
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
