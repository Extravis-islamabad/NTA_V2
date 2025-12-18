<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-white/5">
        <thead class="bg-purple-500/10">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Source IP</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Src Port</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Destination IP</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Dst Port</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Protocol</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Application</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Bytes</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Packets</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($flowDetails as $flow)
            <tr class="hover:bg-white/5">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                    {{ $flow->created_at->format('H:i:s') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $flow->source_ip }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $flow->source_port }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $flow->destination_ip }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $flow->destination_port }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-500/20 text-blue-400 border border-blue-500/30">
                        {{ $flow->protocol }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $flow->application ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $flow->formatted_bytes }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ number_format($flow->packets) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-6 py-12 text-center text-gray-400">
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