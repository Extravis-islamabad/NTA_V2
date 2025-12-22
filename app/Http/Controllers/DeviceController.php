<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceInterface;
use App\Models\Setting;
use App\Services\TrafficAnalysisService;
use App\Services\CloudProviderService;
use App\Services\ASLookupService;
use App\Services\SSHService;
use App\Services\SNMPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{
    protected TrafficAnalysisService $trafficService;
    protected CloudProviderService $cloudService;
    protected ASLookupService $asService;
    protected SSHService $sshService;
    protected SNMPService $snmpService;

    public function __construct(
        TrafficAnalysisService $trafficService,
        CloudProviderService $cloudService,
        ASLookupService $asService,
        SSHService $sshService,
        SNMPService $snmpService
    ) {
        $this->trafficService = $trafficService;
        $this->cloudService = $cloudService;
        $this->asService = $asService;
        $this->sshService = $sshService;
        $this->snmpService = $snmpService;
    }

    public function index(Request $request)
    {
        $query = Device::with('interfaces');

        // Search filter
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Type filter
        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        // Status filter
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $devices = $query->orderBy('name')->get();

        return view('devices.index', compact('devices'));
    }

    public function show(Device $device, Request $request)
    {
        $tab = $request->get('tab', 'overview');
        $timeRange = $request->get('range', '1hour');

        // Always load basic device info
        $device->load('interfaces');

        // Load tab-specific data only for performance
        $data = match($tab) {
            'overview' => $this->getOverviewData($device, $timeRange),
            'flows' => $this->getFlowsData($device, $timeRange),
            'endpoints' => $this->getEndpointsData($device, $timeRange),
            'applications' => $this->getApplicationsData($device, $timeRange),
            'network' => $this->getNetworkData($device, $timeRange),
            default => $this->getOverviewData($device, $timeRange),
        };

        return view('devices.show', array_merge(
            compact('device', 'tab', 'timeRange'),
            $data
        ));
    }

    /**
     * Get data for Overview tab (Summary + Traffic)
     */
    private function getOverviewData(Device $device, string $timeRange): array
    {
        $summaryData = $this->trafficService->getDeviceSummary($device, $timeRange);
        $trafficDistribution = $this->trafficService->getTrafficDistribution($device, $timeRange);
        $trafficTimeSeries = $this->trafficService->getTrafficTimeSeries($device, $timeRange);

        $trafficByApp = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->whereNotNull('application')
            ->select('application')
            ->selectRaw('SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('application')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get();

        $trafficByProtocol = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->select('protocol')
            ->selectRaw('SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('protocol')
            ->orderByDesc('total_bytes')
            ->get();

        return compact('summaryData', 'trafficDistribution', 'trafficTimeSeries', 'trafficByApp', 'trafficByProtocol');
    }

    /**
     * Get data for Flows tab (Flow Details + Conversations)
     */
    private function getFlowsData(Device $device, string $timeRange): array
    {
        $flowDetails = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->latest()
            ->paginate(25);

        // Conversations: Aggregate by source-destination pair only (not by protocol/app)
        // This shows true conversation pairs with all their traffic combined
        $conversations = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->select('source_ip', 'destination_ip')
            ->selectRaw('SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->selectRaw('COUNT(DISTINCT protocol) as protocol_count')
            ->selectRaw('COUNT(DISTINCT application) as app_count')
            ->selectRaw('MIN(created_at) as first_seen')
            ->selectRaw('MAX(created_at) as last_seen')
            ->groupBy('source_ip', 'destination_ip')
            ->orderByDesc('total_bytes')
            ->limit(25)
            ->get();

        return compact('flowDetails', 'conversations');
    }

    /**
     * Get data for Endpoints tab (Sources + Destinations + AS View)
     */
    private function getEndpointsData(Device $device, string $timeRange): array
    {
        $topSources = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->select('source_ip')
            ->selectRaw('SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('source_ip')
            ->orderByDesc('total_bytes')
            ->limit(15)
            ->get();

        $topDestinations = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->select('destination_ip')
            ->selectRaw('SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('destination_ip')
            ->orderByDesc('total_bytes')
            ->limit(15)
            ->get();

        // AS View Data - optimized with limit and caching
        $asTraffic = [];
        $processedCount = 0;
        $maxFlows = 5000; // Limit processing for performance

        $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->select('id', 'source_ip', 'destination_ip', 'bytes')
            ->orderByDesc('bytes')
            ->chunkById(1000, function ($flows) use (&$asTraffic, &$processedCount, $maxFlows) {
                if ($processedCount >= $maxFlows) return false;

                foreach ($flows as $flow) {
                    if ($processedCount >= $maxFlows) break;

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

                    $processedCount++;
                }
            });

        $asTraffic = collect($asTraffic)
            ->map(function($item) {
                $item['total_bytes'] = $item['bytes_sent'] + $item['bytes_received'];
                return $item;
            })
            ->sortByDesc('total_bytes')
            ->values()
            ->take(15);

        return compact('topSources', 'topDestinations', 'asTraffic');
    }

    /**
     * Get data for Applications tab (Applications + Cloud Services)
     */
    private function getApplicationsData(Device $device, string $timeRange): array
    {
        $trafficByApp = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->whereNotNull('application')
            ->select('application')
            ->selectRaw('SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('application')
            ->orderByDesc('total_bytes')
            ->limit(15)
            ->get();

        // Cloud Services Data - optimized with limit
        $cloudTraffic = [];
        $processedCount = 0;
        $maxFlows = 5000;

        $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->select('id', 'destination_ip', 'bytes')
            ->orderByDesc('bytes')
            ->chunkById(1000, function ($flows) use (&$cloudTraffic, &$processedCount, $maxFlows) {
                if ($processedCount >= $maxFlows) return false;

                foreach ($flows as $flow) {
                    if ($processedCount >= $maxFlows) break;

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

                    $processedCount++;
                }
            });

        $cloudTraffic = collect($cloudTraffic)->map(function($item) {
            $item['unique_ips'] = count($item['ips']);
            unset($item['ips']);
            return $item;
        })->sortByDesc('bytes')->values();

        return compact('trafficByApp', 'cloudTraffic');
    }

    /**
     * Get data for Network tab (Interface + QoS)
     */
    private function getNetworkData(Device $device, string $timeRange): array
    {
        $qosData = $device->flows()
            ->where('created_at', '>=', $this->getTimeRangeStart($timeRange))
            ->whereNotNull('dscp')
            ->select('dscp')
            ->selectRaw('SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('dscp')
            ->orderByDesc('total_bytes')
            ->get();

        $collectorIp = Setting::get('collector_ip') ?: request()->server('SERVER_ADDR');
        $collectorPort = Setting::get('netflow_port');
        $netflowConfig = ($collectorIp && $collectorPort)
            ? $this->sshService->getNetFlowConfigTemplate($device->type, $collectorIp, $collectorPort)
            : 'Configure collector IP and port in Settings first.';

        return compact('qosData', 'netflowConfig');
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
            // SSH fields
            'ssh_enabled' => 'nullable|boolean',
            'ssh_host' => 'nullable|string|max:255',
            'ssh_port' => 'nullable|integer|min:1|max:65535',
            'ssh_username' => 'nullable|string|max:255',
            'ssh_password' => 'nullable|string',
            'ssh_private_key' => 'nullable|string',
            // SNMP fields
            'snmp_enabled' => 'nullable|boolean',
            'snmp_version' => 'nullable|in:v1,v2c,v3',
            'snmp_port' => 'nullable|integer|min:1|max:65535',
            'snmp_community' => 'nullable|string|max:255',
            'snmp_username' => 'nullable|string|max:255',
            'snmp_security_level' => 'nullable|in:noAuthNoPriv,authNoPriv,authPriv',
            'snmp_auth_protocol' => 'nullable|in:MD5,SHA,SHA256,SHA512',
            'snmp_auth_password' => 'nullable|string',
            'snmp_priv_protocol' => 'nullable|in:DES,AES,AES192,AES256',
            'snmp_priv_password' => 'nullable|string',
            'snmp_poll_interval' => 'nullable|integer|min:60|max:3600',
        ]);

        $validated['status'] = 'offline';
        $validated['ssh_enabled'] = $request->has('ssh_enabled');
        $validated['snmp_enabled'] = $request->has('snmp_enabled');

        $device = Device::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Device created successfully',
                'data' => $device
            ], 201);
        }

        return redirect()->route('devices.show', $device)
            ->with('success', 'Device added successfully!');
    }

    public function edit(Device $device)
    {
        return view('devices.edit', compact('device'));
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
            // SSH fields
            'ssh_enabled' => 'nullable|boolean',
            'ssh_host' => 'nullable|string|max:255',
            'ssh_port' => 'nullable|integer|min:1|max:65535',
            'ssh_username' => 'nullable|string|max:255',
            'ssh_password' => 'nullable|string',
            'ssh_private_key' => 'nullable|string',
            // SNMP fields
            'snmp_enabled' => 'nullable|boolean',
            'snmp_version' => 'nullable|in:v1,v2c,v3',
            'snmp_port' => 'nullable|integer|min:1|max:65535',
            'snmp_community' => 'nullable|string|max:255',
            'snmp_username' => 'nullable|string|max:255',
            'snmp_security_level' => 'nullable|in:noAuthNoPriv,authNoPriv,authPriv',
            'snmp_auth_protocol' => 'nullable|in:MD5,SHA,SHA256,SHA512',
            'snmp_auth_password' => 'nullable|string',
            'snmp_priv_protocol' => 'nullable|in:DES,AES,AES192,AES256',
            'snmp_priv_password' => 'nullable|string',
            'snmp_poll_interval' => 'nullable|integer|min:60|max:3600',
        ]);

        if ($request->has('ssh_enabled')) {
            $validated['ssh_enabled'] = (bool) $request->ssh_enabled;
        }
        if ($request->has('snmp_enabled')) {
            $validated['snmp_enabled'] = (bool) $request->snmp_enabled;
        }

        // Don't overwrite passwords if not provided
        if (empty($validated['ssh_password'])) {
            unset($validated['ssh_password']);
        }
        if (empty($validated['ssh_private_key'])) {
            unset($validated['ssh_private_key']);
        }
        if (empty($validated['snmp_community'])) {
            unset($validated['snmp_community']);
        }
        if (empty($validated['snmp_auth_password'])) {
            unset($validated['snmp_auth_password']);
        }
        if (empty($validated['snmp_priv_password'])) {
            unset($validated['snmp_priv_password']);
        }

        $device->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Device updated successfully',
                'data' => $device
            ]);
        }

        return redirect()->route('devices.show', $device)
            ->with('success', 'Device updated successfully!');
    }

    public function destroy(Device $device)
    {
        // Delete all related data first (cascade delete)
        DB::transaction(function () use ($device) {
            // Delete all flows associated with this device
            $device->flows()->delete();

            // Delete all traffic statistics
            $device->trafficStatistics()->delete();

            // Delete all alarms
            $device->alarms()->delete();

            // Delete all interfaces
            $device->interfaces()->delete();

            // Delete bandwidth samples
            \App\Models\BandwidthSample::where('device_id', $device->id)->delete();

            // Finally delete the device
            $device->forceDelete();
        });

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Device and all related data deleted successfully'
            ]);
        }

        return redirect()->route('devices.index')
            ->with('success', 'Device and all related data deleted successfully');
    }

    public function testSshConnection(Device $device)
    {
        $result = $this->sshService->testConnection($device);

        return response()->json($result);
    }

    public function pushNetFlowConfig(Request $request, Device $device)
    {
        $collectorIp = $request->input('collector_ip') ?: Setting::get('collector_ip');
        $collectorPort = $request->input('collector_port') ?: Setting::get('netflow_port');

        if (!$collectorIp || !$collectorPort) {
            return response()->json([
                'success' => false,
                'message' => 'Collector IP and Port must be configured in Settings first.'
            ], 400);
        }

        $result = $this->sshService->pushNetFlowConfig($device, $collectorIp, $collectorPort);

        return response()->json($result);
    }

    public function getNetFlowConfig(Device $device)
    {
        $collectorIp = Setting::get('collector_ip');
        $collectorPort = Setting::get('netflow_port');

        if (!$collectorIp || !$collectorPort) {
            return response()->json([
                'success' => false,
                'message' => 'Configure Collector IP and Port in Settings first.',
                'config' => '',
                'collector_ip' => '',
                'collector_port' => ''
            ]);
        }

        $config = $this->sshService->getNetFlowConfigTemplate($device->type, $collectorIp, $collectorPort);

        return response()->json([
            'success' => true,
            'config' => $config,
            'collector_ip' => $collectorIp,
            'collector_port' => $collectorPort
        ]);
    }

    // SNMP Methods
    public function testSnmpConnection(Device $device)
    {
        $result = $this->snmpService->testConnection($device);
        return response()->json($result);
    }

    public function pollSnmpDevice(Device $device)
    {
        try {
            $result = $this->snmpService->pollDevice($device);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function pollSnmpSystemInfo(Device $device)
    {
        try {
            $result = $this->snmpService->pollSystemInfo($device);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function pollSnmpInterfaces(Device $device)
    {
        try {
            $result = $this->snmpService->pollInterfaces($device);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getSnmpStatus(Device $device)
    {
        return response()->json([
            'success' => true,
            'snmp_available' => SNMPService::isAvailable(),
            'snmp_enabled' => $device->snmp_enabled,
            'snmp_version' => $device->snmp_version,
            'has_credentials' => $device->hasSnmpCredentials(),
            'last_poll' => $device->last_snmp_poll?->toIso8601String(),
            'connection_status' => $device->snmp_connection_status,
            'sys_name' => $device->snmp_sys_name,
            'sys_descr' => $device->snmp_sys_descr,
            'sys_uptime' => $device->formatted_uptime,
            'sys_location' => $device->snmp_sys_location,
            'sys_contact' => $device->snmp_sys_contact
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
