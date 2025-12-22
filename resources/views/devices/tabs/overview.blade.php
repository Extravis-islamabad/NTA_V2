<!-- Stats Cards Row with Labels -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="glass-card rounded-xl p-5 border-l-4 border-cyan-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Total Flows</p>
                <p class="text-2xl font-bold text-white">{{ number_format($summaryData['total_flows'] ?? 0) }}</p>
            </div>
            <div class="w-10 h-10 bg-cyan-500/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="glass-card rounded-xl p-5 border-l-4 border-indigo-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Total Traffic</p>
                <p class="text-2xl font-bold text-white">
                    @php
                        $bytes = $summaryData['total_bytes'] ?? 0;
                        if ($bytes >= 1073741824) {
                            echo round($bytes / 1073741824, 2) . ' GB';
                        } else {
                            echo round($bytes / 1048576, 2) . ' MB';
                        }
                    @endphp
                </p>
            </div>
            <div class="w-10 h-10 bg-indigo-500/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="glass-card rounded-xl p-5 border-l-4 border-emerald-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Total Packets</p>
                <p class="text-2xl font-bold text-white">{{ number_format($summaryData['total_packets'] ?? 0) }}</p>
            </div>
            <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="glass-card rounded-xl p-5 border-l-4 border-amber-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Avg Bandwidth</p>
                <p class="text-2xl font-bold text-white">
                    @php
                        $avgBw = ($summaryData['avg_bandwidth'] ?? 0) * 8;
                        if ($avgBw >= 1000000000) {
                            echo round($avgBw / 1000000000, 2) . ' Gbps';
                        } elseif ($avgBw >= 1000000) {
                            echo round($avgBw / 1000000, 2) . ' Mbps';
                        } else {
                            echo round($avgBw / 1000, 2) . ' Kbps';
                        }
                    @endphp
                </p>
            </div>
            <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Traffic Distribution and Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Traffic Distribution Donut -->
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="px-5 py-3 border-b border-white/10">
            <h3 class="text-base font-semibold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                </svg>
                Traffic Direction
            </h3>
        </div>
        <div class="p-4">
            <div id="trafficDistributionChart" style="height: 180px;"></div>
            <div class="mt-3 grid grid-cols-2 gap-3">
                <div class="bg-blue-500/10 rounded-lg p-3 border border-blue-500/20">
                    <div class="flex items-center gap-1.5 mb-1">
                        <svg class="w-3 h-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                        <p class="text-[10px] text-gray-400 font-medium uppercase">Inbound</p>
                    </div>
                    <p class="text-lg font-bold text-blue-400">
                        @php
                            $inbound = $trafficDistribution['inbound_bytes'] ?? 0;
                            echo $inbound >= 1073741824 ? round($inbound / 1073741824, 1) . ' GB' : round($inbound / 1048576, 1) . ' MB';
                        @endphp
                    </p>
                    <p class="text-[10px] text-gray-500">{{ $trafficDistribution['inbound_percent'] ?? 0 }}%</p>
                </div>
                <div class="bg-emerald-500/10 rounded-lg p-3 border border-emerald-500/20">
                    <div class="flex items-center gap-1.5 mb-1">
                        <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                        <p class="text-[10px] text-gray-400 font-medium uppercase">Outbound</p>
                    </div>
                    <p class="text-lg font-bold text-emerald-400">
                        @php
                            $outbound = $trafficDistribution['outbound_bytes'] ?? 0;
                            echo $outbound >= 1073741824 ? round($outbound / 1073741824, 1) . ' GB' : round($outbound / 1048576, 1) . ' MB';
                        @endphp
                    </p>
                    <p class="text-[10px] text-gray-500">{{ $trafficDistribution['outbound_percent'] ?? 0 }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Applications Donut Chart -->
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="px-5 py-3 border-b border-white/10">
            <h3 class="text-base font-semibold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Top Applications
            </h3>
        </div>
        <div class="p-4">
            @if(isset($trafficByApp) && $trafficByApp->isNotEmpty())
                <div id="applicationsDonutChart" style="height: 280px;"></div>
            @else
                <div class="flex flex-col items-center justify-center h-64 text-gray-500">
                    <svg class="w-12 h-12 mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-sm">No application data</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Top Protocols Bar Chart -->
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="px-5 py-3 border-b border-white/10">
            <h3 class="text-base font-semibold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Protocol Distribution
            </h3>
        </div>
        <div class="p-4">
            @if(isset($trafficByProtocol) && $trafficByProtocol->isNotEmpty())
                <div id="protocolBarChart" style="height: 280px;"></div>
            @else
                <div class="flex flex-col items-center justify-center h-64 text-gray-500">
                    <p class="text-sm">No protocol data</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Traffic Over Time Chart -->
<div class="glass-card rounded-xl overflow-hidden">
    <div class="px-5 py-3 border-b border-white/10">
        <h3 class="text-base font-semibold text-white flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
            Traffic Over Time
        </h3>
    </div>
    <div class="p-4">
        <div id="trafficTimeChart" style="height: 280px;"></div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') return;

    const chartColors = ['#22d3ee', '#10B981', '#818cf8', '#F59E0B', '#EF4444', '#EC4899', '#3B82F6', '#84CC16'];

    function formatBytes(bytes) {
        if (!bytes || bytes === 0) return '0 MB';
        if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(1) + ' GB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    // Traffic Distribution Donut
    const inbound = {{ $trafficDistribution['inbound_bytes'] ?? 0 }};
    const outbound = {{ $trafficDistribution['outbound_bytes'] ?? 0 }};

    if (inbound > 0 || outbound > 0) {
        new ApexCharts(document.querySelector("#trafficDistributionChart"), {
            chart: { type: 'donut', height: 180, background: 'transparent' },
            series: [inbound, outbound],
            labels: ['Inbound', 'Outbound'],
            colors: ['#3B82F6', '#10B981'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                color: '#9ca3af',
                                formatter: (w) => formatBytes(w.globals.seriesTotals.reduce((a, b) => a + b, 0))
                            },
                            value: { color: '#fff', formatter: (val) => formatBytes(parseInt(val)) }
                        }
                    }
                }
            },
            stroke: { width: 2, colors: ['rgba(15, 15, 26, 0.8)'] },
            legend: { show: false },
            dataLabels: { enabled: false },
            tooltip: { theme: 'dark', y: { formatter: formatBytes } }
        }).render();
    }

    // Applications Donut Chart
    const appData = @json($trafficByApp ?? []);
    if (appData && appData.length > 0) {
        new ApexCharts(document.querySelector("#applicationsDonutChart"), {
            chart: { type: 'donut', height: 280, background: 'transparent' },
            series: appData.map(item => parseInt(item.total_bytes)),
            labels: appData.map(item => item.application || 'Unknown'),
            colors: chartColors,
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            name: { color: '#9ca3af' },
                            value: { color: '#fff', formatter: (val) => formatBytes(parseInt(val)) },
                            total: { show: true, color: '#9ca3af', formatter: (w) => formatBytes(w.globals.seriesTotals.reduce((a, b) => a + b, 0)) }
                        }
                    }
                }
            },
            stroke: { width: 2, colors: ['rgba(15, 15, 26, 0.8)'] },
            legend: { position: 'bottom', fontSize: '10px', labels: { colors: '#9ca3af' } },
            dataLabels: { enabled: false },
            tooltip: { theme: 'dark', y: { formatter: formatBytes } }
        }).render();
    }

    // Protocol Bar Chart
    const protocolData = @json($trafficByProtocol ?? []);
    if (protocolData && protocolData.length > 0) {
        new ApexCharts(document.querySelector("#protocolBarChart"), {
            chart: { type: 'bar', height: 280, background: 'transparent', foreColor: '#9ca3af', toolbar: { show: false } },
            series: [{ name: 'Traffic', data: protocolData.map(p => parseInt(p.total_bytes)) }],
            xaxis: {
                categories: protocolData.map(p => p.protocol),
                labels: { style: { colors: '#9ca3af', fontSize: '10px' } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: { labels: { formatter: formatBytes, style: { colors: '#9ca3af', fontSize: '10px' } } },
            colors: chartColors,
            plotOptions: { bar: { borderRadius: 6, columnWidth: '70%', distributed: true } },
            legend: { show: false },
            dataLabels: { enabled: false },
            grid: { borderColor: 'rgba(255, 255, 255, 0.1)' },
            tooltip: { theme: 'dark', y: { formatter: formatBytes } }
        }).render();
    }

    // Traffic Time Series
    const timeSeriesData = @json($trafficTimeSeries ?? ['labels' => [], 'bytes' => [], 'packets' => []]);
    if (timeSeriesData.labels && timeSeriesData.labels.length > 0) {
        new ApexCharts(document.querySelector("#trafficTimeChart"), {
            chart: { type: 'area', height: 280, background: 'transparent', foreColor: '#9ca3af', toolbar: { show: true } },
            series: [
                { name: 'Traffic', data: timeSeriesData.bytes },
                { name: 'Packets', data: timeSeriesData.packets || [] }
            ],
            xaxis: {
                categories: timeSeriesData.labels,
                labels: { style: { colors: '#9ca3af', fontSize: '10px' }, rotate: -45 },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: [
                { labels: { formatter: formatBytes, style: { colors: '#22d3ee' } } },
                { opposite: true, labels: { formatter: (v) => v >= 1000 ? (v/1000).toFixed(0) + 'K' : v, style: { colors: '#10B981' } } }
            ],
            colors: ['#22d3ee', '#10B981'],
            fill: { type: 'gradient', gradient: { opacityFrom: 0.5, opacityTo: 0.1 } },
            stroke: { curve: 'smooth', width: [2, 2] },
            legend: { position: 'top', horizontalAlign: 'right', fontSize: '11px', labels: { colors: '#9ca3af' } },
            grid: { borderColor: 'rgba(255, 255, 255, 0.1)' },
            tooltip: {
                theme: 'dark',
                shared: true,
                y: {
                    formatter: function(value, { seriesIndex }) {
                        if (seriesIndex === 0) {
                            return formatBytes(value);
                        } else {
                            return value >= 1000 ? (value/1000).toFixed(1) + 'K' : value;
                        }
                    }
                }
            }
        }).render();
    } else {
        document.getElementById('trafficTimeChart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500"><p>No traffic data for this time range</p></div>';
    }
});
</script>
@endpush
