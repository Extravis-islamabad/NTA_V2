<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Flow;
use App\Models\TrafficStatistic;
use App\Services\TrafficAnalysisService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    protected TrafficAnalysisService $trafficService;

    public function __construct(TrafficAnalysisService $trafficService)
    {
        $this->trafficService = $trafficService;
    }

    public function index()
    {
        $stats = [
            'total_devices' => Device::count(),
            'online_devices' => Device::where('status', 'online')->count(),
            'total_flows' => Flow::count(),
            'total_bytes' => Flow::sum('bytes'),
        ];

        return view('reports.index', compact('stats'));
    }

    public function trafficReport(Request $request)
    {
        $devices = Device::all();

        // Handle initial page load without date parameters
        if (!$request->has('start_date')) {
            return view('reports.traffic', compact('devices'));
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'device_id' => 'nullable|exists:devices,id',
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);

        $query = Flow::whereBetween('created_at', [$start, $end]);

        if (!empty($validated['device_id'])) {
            $query->where('device_id', $validated['device_id']);
        }

        $totalBytes = $query->sum('bytes');
        $totalPackets = $query->sum('packets');
        $totalFlows = $query->count();

        // Calculate average bandwidth
        $durationSeconds = $end->diffInSeconds($start);
        $avgBandwidth = $durationSeconds > 0 ? ($totalBytes * 8 / $durationSeconds) : 0;

        $topApplications = (clone $query)
            ->whereNotNull('application')
            ->selectRaw('application, SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->groupBy('application')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get();

        $topProtocols = (clone $query)
            ->selectRaw('protocol, SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->groupBy('protocol')
            ->orderByDesc('total_bytes')
            ->get();

        // Time series data for traffic chart (PostgreSQL compatible)
        $trafficTimeSeries = (clone $query)
            ->selectRaw("TO_CHAR(created_at, 'YYYY-MM-DD HH24:00:00') as time_bucket, SUM(bytes) as total_bytes")
            ->groupBy('time_bucket')
            ->orderBy('time_bucket')
            ->get();

        return view('reports.traffic', compact(
            'totalBytes',
            'totalPackets',
            'totalFlows',
            'avgBandwidth',
            'topApplications',
            'topProtocols',
            'trafficTimeSeries',
            'start',
            'end',
            'devices'
        ));
    }

    public function deviceReport(Request $request)
    {
        $devices = Device::withCount('flows')
            ->with('interfaces')
            ->get();

        $devicesByType = $devices->groupBy('type')->map->count();
        $devicesByStatus = $devices->groupBy('status')->map->count();

        return view('reports.devices', compact('devices', 'devicesByType', 'devicesByStatus'));
    }

    public function talkersReport(Request $request)
    {
        $devices = Device::all();

        // Handle initial page load without date parameters
        if (!$request->has('start_date')) {
            return view('reports.talkers', compact('devices'));
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'device_id' => 'nullable|exists:devices,id',
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);

        $query = Flow::whereBetween('created_at', [$start, $end]);

        if (!empty($validated['device_id'])) {
            $query->where('device_id', $validated['device_id']);
        }

        $totalBytes = $query->sum('bytes');
        $totalFlows = $query->count();

        // Top Sources
        $topSources = (clone $query)
            ->select('source_ip')
            ->selectRaw('SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->selectRaw('COUNT(DISTINCT destination_ip) as unique_destinations')
            ->groupBy('source_ip')
            ->orderByDesc('total_bytes')
            ->limit(20)
            ->get();

        // Top Destinations
        $topDestinations = (clone $query)
            ->select('destination_ip')
            ->selectRaw('SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->selectRaw('COUNT(DISTINCT source_ip) as unique_sources')
            ->groupBy('destination_ip')
            ->orderByDesc('total_bytes')
            ->limit(20)
            ->get();

        // Top Conversations
        $topConversations = (clone $query)
            ->select('source_ip', 'destination_ip', 'protocol')
            ->selectRaw('SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->groupBy('source_ip', 'destination_ip', 'protocol')
            ->orderByDesc('total_bytes')
            ->limit(25)
            ->get();

        // Top Source-Destination Pairs
        $topPairs = (clone $query)
            ->select('source_ip', 'destination_ip')
            ->selectRaw('SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('source_ip', 'destination_ip')
            ->orderByDesc('total_bytes')
            ->limit(15)
            ->get();

        return view('reports.talkers', compact(
            'topSources',
            'topDestinations',
            'topConversations',
            'topPairs',
            'totalBytes',
            'totalFlows',
            'start',
            'end',
            'devices'
        ));
    }

    public function exportReport(Request $request)
    {
        $type = $request->get('type', 'traffic');

        if ($type === 'traffic') {
            return $this->exportTrafficReport($request);
        } elseif ($type === 'talkers') {
            return $this->exportTalkersReport($request);
        }

        return $this->exportDeviceReport($request);
    }

    private function exportTrafficReport(Request $request)
    {
        $start = Carbon::parse($request->start_date ?? now()->subDay());
        $end = Carbon::parse($request->end_date ?? now());

        $flows = Flow::whereBetween('created_at', [$start, $end])
            ->with('device')
            ->limit(10000)
            ->get();

        $filename = 'traffic_report_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($flows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Device', 'Source IP', 'Destination IP', 'Source Port', 'Destination Port', 'Protocol', 'Bytes', 'Packets', 'Application', 'DSCP', 'Timestamp']);

            foreach ($flows as $flow) {
                fputcsv($file, [
                    $flow->device->name ?? 'Unknown',
                    $flow->source_ip,
                    $flow->destination_ip,
                    $flow->source_port,
                    $flow->destination_port,
                    $flow->protocol,
                    $flow->bytes,
                    $flow->packets,
                    $flow->application ?? 'N/A',
                    $flow->dscp ?? 'N/A',
                    $flow->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportTalkersReport(Request $request)
    {
        $start = Carbon::parse($request->start_date ?? now()->subDay());
        $end = Carbon::parse($request->end_date ?? now());

        $topSources = Flow::whereBetween('created_at', [$start, $end])
            ->select('source_ip')
            ->selectRaw('SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->groupBy('source_ip')
            ->orderByDesc('total_bytes')
            ->limit(100)
            ->get();

        $filename = 'top_talkers_report_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($topSources) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['IP Address', 'Total Bytes', 'Total Packets', 'Flow Count']);

            foreach ($topSources as $source) {
                fputcsv($file, [
                    $source->source_ip,
                    $source->total_bytes,
                    $source->total_packets,
                    $source->flow_count,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportDeviceReport(Request $request)
    {
        $devices = Device::with('interfaces')->get();

        $filename = 'device_report_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($devices) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Device Name', 'IP Address', 'Type', 'Status', 'Interfaces', 'Total Flows', 'Location', 'Group', 'Last Seen']);

            foreach ($devices as $device) {
                fputcsv($file, [
                    $device->name,
                    $device->ip_address,
                    $device->type,
                    $device->status,
                    $device->interface_count,
                    $device->flow_count,
                    $device->location ?? 'N/A',
                    $device->device_group ?? 'N/A',
                    $device->last_seen_at ? $device->last_seen_at->format('Y-m-d H:i:s') : 'Never',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export traffic report as PDF
     */
    public function trafficReportPdf(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'device_id' => 'nullable|exists:devices,id',
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);

        $query = Flow::whereBetween('created_at', [$start, $end]);

        if (!empty($validated['device_id'])) {
            $query->where('device_id', $validated['device_id']);
        }

        $totalBytes = $query->sum('bytes');
        $totalPackets = $query->sum('packets');
        $totalFlows = $query->count();

        $durationSeconds = $end->diffInSeconds($start);
        $avgBandwidth = $durationSeconds > 0 ? ($totalBytes * 8 / $durationSeconds) : 0;

        $topApplications = (clone $query)
            ->whereNotNull('application')
            ->selectRaw('application, SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->groupBy('application')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get();

        $topProtocols = (clone $query)
            ->selectRaw('protocol, SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->groupBy('protocol')
            ->orderByDesc('total_bytes')
            ->get();

        $topSources = (clone $query)
            ->select('source_ip')
            ->selectRaw('SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->groupBy('source_ip')
            ->orderByDesc('total_bytes')
            ->limit(15)
            ->get();

        $topDestinations = (clone $query)
            ->select('destination_ip')
            ->selectRaw('SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->groupBy('destination_ip')
            ->orderByDesc('total_bytes')
            ->limit(15)
            ->get();

        $devices = Device::all();
        $selectedDevice = !empty($validated['device_id']) ? Device::find($validated['device_id']) : null;

        $pdf = Pdf::loadView('reports.pdf.traffic', compact(
            'totalBytes',
            'totalPackets',
            'totalFlows',
            'avgBandwidth',
            'topApplications',
            'topProtocols',
            'topSources',
            'topDestinations',
            'start',
            'end',
            'devices',
            'selectedDevice'
        ));

        $pdf->setPaper('a4', 'portrait');

        $filename = 'traffic_report_' . now()->format('Y-m-d_His') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export device report as PDF
     */
    public function deviceReportPdf(Request $request)
    {
        $devices = Device::withCount('flows')
            ->with('interfaces')
            ->get();

        $devicesByType = $devices->groupBy('type')->map->count();
        $devicesByStatus = $devices->groupBy('status')->map->count();

        $stats = [
            'total_devices' => Device::count(),
            'online_devices' => Device::where('status', 'online')->count(),
            'offline_devices' => Device::where('status', 'offline')->count(),
            'total_flows' => Flow::count(),
            'total_bytes' => Flow::sum('bytes'),
        ];

        $pdf = Pdf::loadView('reports.pdf.devices', compact(
            'devices',
            'devicesByType',
            'devicesByStatus',
            'stats'
        ));

        $pdf->setPaper('a4', 'landscape');

        $filename = 'device_report_' . now()->format('Y-m-d_His') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export talkers report as PDF
     */
    public function talkersReportPdf(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'device_id' => 'nullable|exists:devices,id',
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);

        $query = Flow::whereBetween('created_at', [$start, $end]);

        if (!empty($validated['device_id'])) {
            $query->where('device_id', $validated['device_id']);
        }

        $totalBytes = $query->sum('bytes');
        $totalFlows = $query->count();

        $topSources = (clone $query)
            ->select('source_ip')
            ->selectRaw('SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->selectRaw('COUNT(DISTINCT destination_ip) as unique_destinations')
            ->groupBy('source_ip')
            ->orderByDesc('total_bytes')
            ->limit(20)
            ->get();

        $topDestinations = (clone $query)
            ->select('destination_ip')
            ->selectRaw('SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->selectRaw('COUNT(DISTINCT source_ip) as unique_sources')
            ->groupBy('destination_ip')
            ->orderByDesc('total_bytes')
            ->limit(20)
            ->get();

        $topConversations = (clone $query)
            ->select('source_ip', 'destination_ip', 'protocol')
            ->selectRaw('SUM(bytes) as total_bytes, SUM(packets) as total_packets, COUNT(*) as flow_count')
            ->groupBy('source_ip', 'destination_ip', 'protocol')
            ->orderByDesc('total_bytes')
            ->limit(25)
            ->get();

        $devices = Device::all();
        $selectedDevice = !empty($validated['device_id']) ? Device::find($validated['device_id']) : null;

        $pdf = Pdf::loadView('reports.pdf.talkers', compact(
            'topSources',
            'topDestinations',
            'topConversations',
            'totalBytes',
            'totalFlows',
            'start',
            'end',
            'devices',
            'selectedDevice'
        ));

        $pdf->setPaper('a4', 'portrait');

        $filename = 'top_talkers_report_' . now()->format('Y-m-d_His') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes): string
    {
        if ($bytes >= 1099511627776) {
            return round($bytes / 1099511627776, 2) . ' TB';
        } elseif ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}
