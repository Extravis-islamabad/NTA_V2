<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-white/5">
        <thead class="bg-purple-500/10">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Source IP</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Destination IP</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Protocol</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Application</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Flows</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Bytes</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Packets</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($conversations as $conv)
            <tr class="hover:bg-white/5">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $conv->source_ip }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $conv->destination_ip }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-500/20 text-blue-400 border border-blue-500/30">
                        {{ $conv->protocol }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $conv->application ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ number_format($conv->flow_count) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                    @php
                        $bytes = $conv->total_bytes;
                        if ($bytes >= 1073741824) {
                            echo round($bytes / 1073741824, 2) . ' GB';
                        } elseif ($bytes >= 1048576) {
                            echo round($bytes / 1048576, 2) . ' MB';
                        } else {
                            echo round($bytes / 1024, 2) . ' KB';
                        }
                    @endphp
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ number_format($conv->total_packets) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                    No conversation data available
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>