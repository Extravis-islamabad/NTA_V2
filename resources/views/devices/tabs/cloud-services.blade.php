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
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Cloud Provider Distribution</h3>
        <div id="cloudProviderChart" style="height: 280px;"></div>
    </div>

    <!-- Cloud Stats -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Cloud Traffic Summary</h3>
        <div class="space-y-4">
            @php $totalBytes = $cloudTraffic->sum('bytes'); @endphp
            @foreach($cloudTraffic->take(5) as $cloud)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full" style="background-color: {{ ['#FF9900', '#0078D4', '#4285F4', '#F38020', '#0080FF'][$loop->index % 5] }}"></div>
                    <span class="font-medium text-gray-900">{{ $cloud['provider'] }}</span>
                </div>
                <div class="text-right">
                    <span class="font-semibold text-gray-900">
                        @php
                            $bytes = $cloud['bytes'];
                            if ($bytes >= 1073741824) {
                                echo number_format($bytes / 1073741824, 2) . ' GB';
                            } elseif ($bytes >= 1048576) {
                                echo number_format($bytes / 1048576, 2) . ' MB';
                            } else {
                                echo number_format($bytes / 1024, 2) . ' KB';
                            }
                        @endphp
                    </span>
                    <span class="text-xs text-gray-500 ml-2">({{ number_format(($cloud['bytes'] / max(1, $totalBytes)) * 100, 1) }}%)</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Cloud Traffic Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900">Cloud Traffic Details</h3>
    </div>
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
                @foreach($cloudTraffic as $cloud)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ ['#FF9900', '#0078D4', '#4285F4', '#F38020', '#0080FF'][$loop->index % 5] }}"></div>
                            <span class="font-medium text-gray-900">{{ $cloud['provider'] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($cloud['flows']) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        @php
                            $bytes = $cloud['bytes'];
                            if ($bytes >= 1073741824) {
                                echo number_format($bytes / 1073741824, 2) . ' GB';
                            } elseif ($bytes >= 1048576) {
                                echo number_format($bytes / 1048576, 2) . ' MB';
                            } else {
                                echo number_format($bytes / 1024, 2) . ' KB';
                            }
                        @endphp
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cloud['unique_ips'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-1 mr-3 max-w-24">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($cloud['bytes'] / max(1, $totalBytes)) * 100 }}%"></div>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">{{ number_format(($cloud['bytes'] / max(1, $totalBytes)) * 100, 1) }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cloudData = @json($cloudTraffic);
    if (!cloudData || cloudData.length === 0) return;

    const chartEl = document.getElementById('cloudProviderChart');
    if (!chartEl) return;

    const colors = ['#FF9900', '#0078D4', '#4285F4', '#F38020', '#0080FF', '#00C7B7'];

    const options = {
        chart: {
            type: 'donut',
            height: 280,
            fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif'
        },
        series: cloudData.map(item => item.bytes),
        labels: cloudData.map(item => item.provider),
        colors: colors,
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
                    labels: {
                        show: true,
                        name: { fontSize: '12px', fontWeight: 600 },
                        value: {
                            fontSize: '14px',
                            fontWeight: 700,
                            formatter: function(val) {
                                val = parseInt(val);
                                if (val >= 1073741824) return (val / 1073741824).toFixed(2) + ' GB';
                                if (val >= 1048576) return (val / 1048576).toFixed(2) + ' MB';
                                return (val / 1024).toFixed(2) + ' KB';
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function(w) {
                                const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                if (total >= 1073741824) return (total / 1073741824).toFixed(2) + ' GB';
                                if (total >= 1048576) return (total / 1048576).toFixed(2) + ' MB';
                                return (total / 1024).toFixed(2) + ' KB';
                            }
                        }
                    }
                }
            }
        },
        stroke: { width: 2, colors: ['#fff'] },
        legend: {
            position: 'bottom',
            fontSize: '11px',
            horizontalAlign: 'center',
            itemMargin: { horizontal: 8, vertical: 4 }
        },
        dataLabels: { enabled: false },
        tooltip: {
            y: {
                formatter: function(val) {
                    if (val >= 1073741824) return (val / 1073741824).toFixed(2) + ' GB';
                    if (val >= 1048576) return (val / 1048576).toFixed(2) + ' MB';
                    return (val / 1024).toFixed(2) + ' KB';
                }
            }
        }
    };

    new ApexCharts(chartEl, options).render();
});
</script>
@endpush
@endif
