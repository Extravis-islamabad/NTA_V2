<div class="mb-6">
    <canvas id="applicationChart" height="300"></canvas>
</div>

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Application</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Flow Count</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Bytes</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Percentage</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @php $totalBytes = $trafficByApp->sum('total_bytes'); @endphp
            @forelse($trafficByApp as $app)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6', '#F97316', '#06B6D4', '#84CC16'][$loop->index % 10] }}"></div>
                        <span class="font-medium text-gray-900">{{ $app->application }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($app->flow_count) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-1 mr-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($app->total_bytes / max(1, $totalBytes)) * 100 }}%"></div>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">{{ number_format(($app->total_bytes / max(1, $totalBytes)) * 100, 2) }}%</span>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                    No application data available
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
if (typeof window.applicationChartInitialized === 'undefined') {
    window.applicationChartInitialized = true;

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') return;

        const canvas = document.getElementById('applicationChart');
        if (!canvas) return;

        // Destroy existing instance
        if (window.applicationChartInstance) {
            window.applicationChartInstance.destroy();
        }

        const appData = @json($trafficByApp);
        if (appData && appData.length > 0) {
            window.applicationChartInstance = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: appData.map(item => item.application),
                    datasets: [{
                        label: 'Bytes',
                        data: appData.map(item => item.total_bytes),
                        backgroundColor: '#3B82F6'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    plugins: { legend: { display: false } },
                    scales: { 
                        y: { 
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1073741824) {
                                        return (value / 1073741824).toFixed(1) + ' GB';
                                    } else if (value >= 1048576) {
                                        return (value / 1048576).toFixed(1) + ' MB';
                                    } else if (value >= 1024) {
                                        return (value / 1024).toFixed(1) + ' KB';
                                    }
                                    return value + ' B';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
}
</script>
@endpush