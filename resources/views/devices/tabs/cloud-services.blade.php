@if($cloudTraffic->isEmpty())
<div class="text-center py-12">
    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
    </svg>
    <h3 class="mt-4 text-lg font-medium text-gray-900">No Cloud Traffic Detected</h3>
    <p class="mt-2 text-sm text-gray-500">No traffic to known cloud service providers found in the selected time range.</p>
</div>
@else
<!-- Cloud Provider Distribution -->
<div class="mb-6">
    <h3 class="text-lg font-semibold mb-4">Cloud Provider Distribution</h3>
    <div style="height: 300px;">
        <canvas id="cloudProviderChart"></canvas>
    </div>
</div>

<!-- Cloud Traffic Table -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cloud Provider</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Flow Count</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Bytes</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unique IPs</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Percentage</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @php $totalBytes = $cloudTraffic->sum('bytes'); @endphp
            @foreach($cloudTraffic as $cloud)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ ['#FF9900', '#0078D4', '#4285F4', '#F38020', '#0080FF'][$loop->index % 5] }}"></div>
                        <span class="font-medium text-gray-900">{{ $cloud['provider'] }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($cloud['flows']) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    @php
                        $bytes = $cloud['bytes'];
                        if ($bytes >= 1073741824) {
                            echo round($bytes / 1073741824, 2) . ' GB';
                        } elseif ($bytes >= 1048576) {
                            echo round($bytes / 1048576, 2) . ' MB';
                        } else {
                            echo round($bytes / 1024, 2) . ' KB';
                        }
                    @endphp
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cloud['unique_ips'] }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-1 mr-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($cloud['bytes'] / max(1, $totalBytes)) * 100 }}%"></div>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">{{ number_format(($cloud['bytes'] / max(1, $totalBytes)) * 100, 2) }}%</span>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
if (typeof window.cloudChartInitialized === 'undefined') {
    window.cloudChartInitialized = true;

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') return;

        const canvas = document.getElementById('cloudProviderChart');
        if (!canvas) return;

        if (window.cloudChartInstance) {
            window.cloudChartInstance.destroy();
        }

        const cloudData = @json($cloudTraffic);
        if (cloudData && cloudData.length > 0) {
            window.cloudChartInstance = new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: cloudData.map(item => item.provider),
                    datasets: [{
                        data: cloudData.map(item => item.bytes),
                        backgroundColor: ['#FF9900', '#0078D4', '#4285F4', '#F38020', '#0080FF', '#00C7B7']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 10
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
@endif