@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ route('traffic.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
            ← Back to Traffic
        </a>
    </div>

    <!-- Device Header -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $device->name }}</h2>
                <p class="text-sm text-gray-500">{{ $device->ip_address }}</p>
            </div>
            <form method="GET" class="flex gap-2">
                <select name="range" class="border-gray-300 rounded-md" onchange="this.form.submit()">
                    <option value="1hour" {{ $timeRange === '1hour' ? 'selected' : '' }}>Last Hour</option>
                    <option value="6hours" {{ $timeRange === '6hours' ? 'selected' : '' }}>Last 6 Hours</option>
                    <option value="24hours" {{ $timeRange === '24hours' ? 'selected' : '' }}>Last 24 Hours</option>
                    <option value="7days" {{ $timeRange === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Top Sources and Destinations -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Sources -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Source IPs</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Source IP</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Flows</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bytes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($topSources as $source)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $source->source_ip }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">{{ number_format($source->flow_count) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">
                                @php
                                    $bytes = $source->total_bytes;
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

        <!-- Top Destinations -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Destination IPs</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Destination IP</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Flows</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bytes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($topDestinations as $dest)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $dest->destination_ip }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">{{ number_format($dest->flow_count) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">
                                @php
                                    $bytes = $dest->total_bytes;
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

    <!-- Recent Flows -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Flows</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destination</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Protocol</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Application</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bytes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Packets</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($flows as $flow)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $flow->created_at->format('H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $flow->source_ip }}:{{ $flow->source_port }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $flow->destination_ip }}:{{ $flow->destination_port }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                {{ $flow->protocol }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $flow->application ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $flow->formatted_bytes }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($flow->packets) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $flows->links() }}
        </div>
    </div>
</div>
@endsection