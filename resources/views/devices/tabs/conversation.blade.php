<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source IP</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destination IP</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Protocol</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Application</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Flows</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bytes</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Packets</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($conversations as $conv)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $conv->source_ip }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $conv->destination_ip }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                        {{ $conv->protocol }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $conv->application ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($conv->flow_count) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($conv->total_packets) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    No conversation data available
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>