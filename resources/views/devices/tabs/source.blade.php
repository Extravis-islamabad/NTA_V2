<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source IP</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Flow Count</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Bytes</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Percentage</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @php $totalBytes = $topSources->sum('total_bytes'); @endphp
            @forelse($topSources as $source)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $source->source_ip }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($source->flow_count) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-1 mr-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($source->total_bytes / max(1, $totalBytes)) * 100 }}%"></div>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">{{ number_format(($source->total_bytes / max(1, $totalBytes)) * 100, 2) }}%</span>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                    No source data available
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>