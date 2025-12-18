<!-- Application Traffic Chart -->
<div class="glass-card rounded-xl border border-white/10 p-6 mb-6">
    <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
        </svg>
        Application Traffic Distribution
    </h3>
    <div id="applicationBarChart" style="height: 350px;"></div>
</div>

<!-- Application Table -->
<div class="glass-card rounded-xl border border-white/10 overflow-hidden">
    <div class="px-6 py-4 border-b border-white/10">
        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-[#C843F3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Application Details
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-white/5">
            <thead class="bg-purple-500/10">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase tracking-wider">Application</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase tracking-wider">Flow Count</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase tracking-wider">Total Bytes</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-300 uppercase tracking-wider">Distribution</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @php
                    $totalBytes = $trafficByApp->sum('total_bytes');
                    $colors = ['#5548F5', '#C843F3', '#9619B5', '#10B981', '#F59E0B', '#3B82F6', '#EF4444', '#14B8A6', '#F97316', '#84CC16'];
                @endphp
                @forelse($trafficByApp as $app)
                @php
                    $color = $colors[$loop->index % count($colors)];
                @endphp
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold" style="background-color: {{ $color }}">
                                {{ strtoupper(substr($app->application, 0, 2)) }}
                            </div>
                            <span class="font-medium text-white">{{ $app->application }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ number_format($app->flow_count) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
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
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <div class="w-full bg-white/10 rounded-full h-2 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500" style="width: {{ ($app->total_bytes / max(1, $totalBytes)) * 100 }}%; background: linear-gradient(90deg, #5548F5, #C843F3);"></div>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-[#5548F5] w-16 text-right">{{ number_format(($app->total_bytes / max(1, $totalBytes)) * 100, 1) }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p class="text-gray-400">No application data available</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') return;

    const appData = @json($trafficByApp);
    if (appData && appData.length > 0) {
        const chartOptions = {
            chart: {
                type: 'bar',
                height: 350,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: false
                    }
                }
            },
            series: [{
                name: 'Traffic',
                data: appData.map(item => parseInt(item.total_bytes))
            }],
            xaxis: {
                categories: appData.map(item => item.application),
                labels: {
                    style: {
                        colors: '#6b7280',
                        fontSize: '11px'
                    },
                    rotate: -45,
                    rotateAlways: appData.length > 5
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return formatBytes(val);
                    },
                    style: {
                        colors: '#6b7280',
                        fontSize: '11px'
                    }
                }
            },
            colors: [window.monetxColors.primary],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.25,
                    gradientToColors: [window.monetxColors.secondary],
                    stops: [0, 100]
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '60%',
                    distributed: false
                }
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return formatBytes(val);
                    }
                }
            },
            grid: {
                borderColor: '#e5e7eb',
                strokeDashArray: 4
            }
        };

        new ApexCharts(document.querySelector("#applicationBarChart"), chartOptions).render();
    } else {
        document.getElementById('applicationBarChart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-400">No application data available</div>';
    }
});
</script>
@endpush
