<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Flow;
use App\Models\TrafficStatistic;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function trafficReport(Request $request)
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

        $topApplications = (clone $query)
            ->whereNotNull('application')
            ->selectRaw('application, SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('application')
            ->orderByDesc('total_bytes')
            ->limit(10)
            ->get();

        $topProtocols = (clone $query)
            ->selectRaw('protocol, SUM(bytes) as total_bytes, COUNT(*) as flow_count')
            ->groupBy('protocol')
            ->orderByDesc('total_bytes')
            ->get();

        $devices = Device::all();

        return view('reports.traffic', compact(
            'totalBytes',
            'totalPackets',
            'totalFlows',
            'topApplications',
            'topProtocols',
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

        return view('reports.devices', compact('devices'));
    }

    public function exportReport(Request $request)
    {
        // Will implement CSV export
        $type = $request->get('type', 'traffic');
        
        if ($type === 'traffic') {
            return $this->exportTrafficReport($request);
        }

        return $this->exportDeviceReport($request);
    }

    private function exportTrafficReport(Request $request)
    {
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        $flows = Flow::whereBetween('created_at', [$start, $end])
            ->with('device')
            ->get();

        $filename = 'traffic_report_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($flows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Device', 'Source IP', 'Destination IP', 'Protocol', 'Bytes', 'Packets', 'Application', 'Timestamp']);

            foreach ($flows as $flow) {
                fputcsv($file, [
                    $flow->device->name,
                    $flow->source_ip,
                    $flow->destination_ip,
                    $flow->protocol,
                    $flow->bytes,
                    $flow->packets,
                    $flow->application ?? 'N/A',
                    $flow->created_at->format('Y-m-d H:i:s'),
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
            fputcsv($file, ['Device Name', 'IP Address', 'Type', 'Status', 'Interfaces', 'Total Flows', 'Location']);

            foreach ($devices as $device) {
                fputcsv($file, [
                    $device->name,
                    $device->ip_address,
                    $device->type,
                    $device->status,
                    $device->interface_count,
                    $device->flow_count,
                    $device->location ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}