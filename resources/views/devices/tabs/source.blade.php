<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-white/5">
        <thead class="bg-purple-500/10">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Source IP</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Flow Count</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Total Bytes</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase">Percentage</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @php $totalBytes = $topSources->sum('total_bytes'); @endphp
            @forelse($topSources as $source)
            <tr class="hover:bg-white/5">
                <td class="px-6 py-4 whitespace-nowrap font-medium text-white">{{ $source->source_ip }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ number_format($source->flow_count) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
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
                            <div class="w-full bg-white/10 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($source->total_bytes / max(1, $totalBytes)) * 100 }}%"></div>
                            </div>
                        </div>
                        <span class="text-sm text-gray-400">{{ number_format(($source->total_bytes / max(1, $totalBytes)) * 100, 2) }}%</span>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                    No source data available
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>