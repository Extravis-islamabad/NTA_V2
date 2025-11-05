<div class="mb-6">
    <canvas id="qosDistributionChart" height="300"></canvas>
</div>

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">DSCP Value</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Flow Count</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Bytes</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Percentage</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @php $totalBytes = $qosData->sum('total_bytes'); @endphp
            @forelse($qosData as $qos)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 text-sm font-semibold rounded bg-purple-100 text-purple-800">
                        AF{{ $qos->dscp }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($qos->flow_count) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    @php
                        $bytes = $qos->total_bytes;
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
                                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($qos->total_bytes / max(1, $totalBytes)) * 100 }}%"></div>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">{{ number_format(($qos->total_bytes / max(1, $totalBytes)) * 100, 2) }}%</span>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                    No QoS data available
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') return;

    const qosData = @json($qosData);
    if (qosData.length > 0) {
        new Chart(document.getElementById('qosDistributionChart'), {
            type: 'pie',
            data: {
                labels: qosData.map(item => 'AF' + item.dscp),
                datasets: [{
                    data: qosData.map(item => item.total_bytes),
                    backgroundColor: ['#8B5CF6', '#EC4899', '#14B8A6', '#F97316', '#06B6D4', '#84CC16', '#EF4444', '#F59E0B', '#10B981', '#3B82F6']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
});
</script>
@endpush