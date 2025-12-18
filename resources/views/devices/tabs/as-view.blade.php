@if($asTraffic->isEmpty())
<div class="text-center py-12">
    <svg class="mx-auto h-16 w-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    <h3 class="mt-4 text-lg font-medium text-white">No AS Data Available</h3>
    <p class="mt-2 text-sm text-gray-400">No Autonomous System data found for the selected time range.</p>
</div>
@else
<!-- AS Traffic Table -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-white/5">
        <thead class="bg-purple-500/10">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">AS Number</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Organization</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Country</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Flows</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Bytes Sent</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Bytes Received</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @foreach($asTraffic as $as)
            <tr class="hover:bg-white/5">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 text-sm font-semibold rounded bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                        AS{{ $as['asn'] }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap font-medium text-white">{{ $as['name'] }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $as['country'] }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ number_format($as['flows']) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                    @php
                        $bytes = $as['bytes_sent'];
                        if ($bytes >= 1073741824) {
                            echo round($bytes / 1073741824, 2) . ' GB';
                        } elseif ($bytes >= 1048576) {
                            echo round($bytes / 1048576, 2) . ' MB';
                        } else {
                            echo round($bytes / 1024, 2) . ' KB';
                        }
                    @endphp
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                    @php
                        $bytes = $as['bytes_received'];
                        if ($bytes >= 1073741824) {
                            echo round($bytes / 1073741824, 2) . ' GB';
                        } elseif ($bytes >= 1048576) {
                            echo round($bytes / 1048576, 2) . ' MB';
                        } else {
                            echo round($bytes / 1024, 2) . ' KB';
                        }
                    @endphp
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                    @php
                        $bytes = $as['total_bytes'];
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
@endif