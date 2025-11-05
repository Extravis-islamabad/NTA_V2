<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source IP</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Src Port</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destination IP</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dst Port</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Protocol</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Application</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bytes</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Packets</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($flowDetails as $flow)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $flow->created_at->format('H:i:s') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $flow->source_ip }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $flow->source_port }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $flow->destination_ip }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $flow->destination_port }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                        {{ $flow->protocol }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $flow->application ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $flow->formatted_bytes }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($flow->packets) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                    No flow data available for this time range
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $flowDetails->appends(['tab' => 'flow-details', 'range' => $timeRange])->links() }}
</div>