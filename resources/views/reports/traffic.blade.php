@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ route('reports.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
            ← Back to Reports
        </a>
    </div>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Traffic Report</h2>
    </div>

    <!-- Report Parameters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Report Parameters</h3>
        <form method="GET" action="{{ route('reports.traffic') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="datetime-local" name="start_date" value="{{ request('start_date', now()->subDay()->format('Y-m-d\TH:i')) }}" class="w-full border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="datetime-local" name="end_date" value="{{ request('end_date', now()->format('Y-m-d\TH:i')) }}" class="w-full border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Device (Optional)</label>
                    <select name="device_id" class="w-full border-gray-300 rounded-md">
                        <option value="">All Devices</option>
                        @foreach($devices as $device)
                            <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>
                                {{ $device->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 flex gap-2">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                    Generate Report
                </button>
                <a href="{{ route('reports.export') }}?type=traffic&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&device_id={{ request('device_id') }}" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                    Export to CSV
                </a>
            </div>
        </form>
    </div>

    @if(isset($totalBytes))
    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Flows</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalFlows) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Bytes</p>
            <p class="text-3xl font-bold text-gray-900">
                @php
                    if ($totalBytes >= 1073741824) {
                        echo round($totalBytes / 1073741824, 2) . ' GB';
                    } elseif ($totalBytes >= 1048576) {
                        echo round($totalBytes / 1048576, 2) . ' MB';
                    } else {
                        echo round($totalBytes / 1024, 2) . ' KB';
                    }
                @endphp
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Packets</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalPackets) }}</p>
        </div>
    </div>

    <!-- Top Applications & Protocols -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Applications</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Application</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Flows</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bytes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($topApplications as $app)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $app->application }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">{{ number_format($app->flow_count) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">
                                @php
                                    $bytes = $app->total_bytes;
                                    if ($bytes >= 1073741824) {
                                        echo round($bytes / 1073741824, 2) . ' GB';
                                    } elseif ($bytes >= 1048576) {
                                        echo round($bytes / 1048576, 2) . ' MB';
                                    } else {
                                        echo round($bytes / 1024, 2) . ' KB';
                                    }
                                @endphp
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Protocols</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Protocol</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Flows</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bytes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($topProtocols as $protocol)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $protocol->protocol }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">{{ number_format($protocol->flow_count) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">
                                @php
                                    $bytes = $protocol->total_bytes;
                                    if ($bytes >= 1073741824) {
                                        echo round($bytes / 1073741824, 2) . ' GB';
                                    } elseif ($bytes >= 1048576) {
                                        echo round($bytes / 1048576, 2) . ' MB';
                                    } else {
                                        echo round($bytes / 1024, 2) . ' KB';
                                    }
                                @endphp
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection