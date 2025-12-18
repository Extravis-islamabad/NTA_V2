<!-- Traffic Distribution and Protocol Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Traffic Distribution -->
    <div class="glass-card rounded-xl overflow-hidden border border-white/10">
        <div class="px-6 py-4 border-b border-white/10 bg-purple-500/10">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                </svg>
                Traffic Distribution
            </h3>
        </div>
        <div class="p-6">
            <div id="trafficDistributionChart" style="height: 220px;"></div>
            <div class="mt-4 grid grid-cols-2 gap-4">
                <div class="bg-blue-500/10 rounded-xl p-4 border border-blue-500/30">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                        <p class="text-xs text-gray-300 font-medium">Inbound Traffic</p>
                    </div>
                    <p class="text-2xl font-bold text-blue-400">
                        @php
                            $inbound = $trafficDistribution['inbound_bytes'] ?? 0;
                            if ($inbound >= 1073741824) {
                                echo number_format($inbound / 1073741824, 2) . ' GB';
                            } elseif ($inbound >= 1048576) {
                                echo number_format($inbound / 1048576, 2) . ' MB';
                            } else {
                                echo number_format($inbound / 1048576, 2) . ' MB';
                            }
                        @endphp
                    </p>
                    <p class="text-xs text-gray-400 mt-1">{{ $trafficDistribution['inbound_percent'] ?? 0 }}% of total</p>
                </div>
                <div class="bg-emerald-500/10 rounded-xl p-4 border border-emerald-500/30">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                        <p class="text-xs text-gray-300 font-medium">Outbound Traffic</p>
                    </div>
                    <p class="text-2xl font-bold text-emerald-400">
                        @php
                            $outbound = $trafficDistribution['outbound_bytes'] ?? 0;
                            if ($outbound >= 1073741824) {
                                echo number_format($outbound / 1073741824, 2) . ' GB';
                            } elseif ($outbound >= 1048576) {
                                echo number_format($outbound / 1048576, 2) . ' MB';
                            } else {
                                echo number_format($outbound / 1048576, 2) . ' MB';
                            }
                        @endphp
                    </p>
                    <p class="text-xs text-gray-400 mt-1">{{ $trafficDistribution['outbound_percent'] ?? 0 }}% of total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Protocols -->
    <div class="glass-card rounded-xl overflow-hidden border border-white/10">
        <div class="px-6 py-4 border-b border-white/10 bg-purple-500/10">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Protocol Distribution
            </h3>
        </div>
        <div class="p-6">
            <div id="protocolPieChart" style="height: 280px;"></div>
        </div>
    </div>
</div>

<!-- Traffic Over Time Chart -->
<div class="mt-6 glass-card rounded-xl overflow-hidden border border-white/10">
    <div class="px-6 py-4 border-b border-white/10 bg-purple-500/10">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
            Traffic Over Time
        </h3>
    </div>
    <div class="p-6">
        <div id="trafficTimeChart" style="height: 350px;"></div>
    </div>
</div>

<!-- Traffic Stats Summary -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="glass-card rounded-xl p-5 border-l-4 border-blue-500">
        <p class="text-sm text-gray-400 font-medium">Total Traffic</p>
        <p class="text-2xl font-bold text-white mt-1">
            @php
                $total = ($trafficDistribution['total_bytes'] ?? 0);
                if ($total >= 1073741824) {
                    echo number_format($total / 1073741824, 2) . ' GB';
                } else {
                    echo number_format($total / 1048576, 2) . ' MB';
                }
            @endphp
        </p>
    </div>
    <div class="glass-card rounded-xl p-5 border-l-4 border-emerald-500">
        <p class="text-sm text-gray-400 font-medium">Total Flows</p>
        <p class="text-2xl font-bold text-white mt-1">{{ number_format($summaryData['total_flows'] ?? 0) }}</p>
    </div>
    <div class="glass-card rounded-xl p-5 border-l-4 border-purple-500">
        <p class="text-sm text-gray-400 font-medium">Total Packets</p>
        <p class="text-2xl font-bold text-white mt-1">{{ number_format($summaryData['total_packets'] ?? 0) }}</p>
    </div>
    <div class="glass-card rounded-xl p-5 border-l-4 border-amber-500">
        <p class="text-sm text-gray-400 font-medium">Avg Bandwidth</p>
        <p class="text-2xl font-bold text-white mt-1">
            @php
                $avgBw = ($summaryData['avg_bandwidth'] ?? 0) * 8;
                if ($avgBw >= 1000000000) {
                    echo number_format($avgBw / 1000000000, 2) . ' Gbps';
                } elseif ($avgBw >= 1000000) {
                    echo number_format($avgBw / 1000000, 2) . ' Mbps';
                } else {
                    echo number_format($avgBw / 1000000, 2) . ' Mbps';
                }
            @endphp
        </p>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') return;

    const inbound = {{ $trafficDistribution['inbound_bytes'] ?? 0 }};
    const outbound = {{ $trafficDistribution['outbound_bytes'] ?? 0 }};

    // Traffic Distribution Donut - Modern Style
    if (inbound > 0 || outbound > 0) {
        const distributionOptions = {
            chart: {
                type: 'donut',
                height: 220,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif'
            },
            series: [inbound, outbound],
            labels: ['Inbound', 'Outbound'],
            colors: ['#3B82F6', '#10B981'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '14px',
                                fontWeight: 600
                            },
                            value: {
                                show: true,
                                fontSize: '16px',
                                fontWeight: 700,
                                formatter: function(val) {
                                    return formatBytesModern(parseInt(val));
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                fontSize: '12px',
                                fontWeight: 500,
                                color: '#6b7280',
                                formatter: function(w) {
                                    const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return formatBytesModern(total);
                                }
                            }
                        }
                    }
                }
            },
            stroke: {
                width: 3,
                colors: ['#fff']
            },
            legend: {
                position: 'bottom',
                fontSize: '12px',
                fontWeight: 500,
                markers: {
                    width: 12,
                    height: 12,
                    radius: 3
                }
            },
            dataLabels: { enabled: false },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return formatBytesModern(val);
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#trafficDistributionChart"), distributionOptions).render();
    } else {
        document.getElementById('trafficDistributionChart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-400"><p>No traffic data available</p></div>';
    }

    // Protocol Bar Chart - Modern Horizontal Style
    const protocolData = @json($trafficByProtocol);
    if (protocolData && protocolData.length > 0) {
        const protocolOptions = {
            chart: {
                type: 'bar',
                height: 280,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 6,
                    barHeight: '60%',
                    distributed: true
                }
            },
            colors: ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444', '#EC4899', '#14B8A6', '#F97316'],
            series: [{
                name: 'Traffic',
                data: protocolData.map(p => parseInt(p.total_bytes))
            }],
            xaxis: {
                categories: protocolData.map(p => p.protocol),
                labels: {
                    formatter: function(val) {
                        return formatBytesModern(val);
                    },
                    style: {
                        colors: '#6b7280',
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#6b7280',
                        fontSize: '12px',
                        fontWeight: 500
                    }
                }
            },
            legend: { show: false },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return formatBytesModern(val);
                },
                style: {
                    fontSize: '11px',
                    fontWeight: 600
                },
                offsetX: 5
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return formatBytesModern(val);
                    }
                }
            },
            grid: {
                borderColor: '#e5e7eb',
                strokeDashArray: 4,
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: false } }
            }
        };
        new ApexCharts(document.querySelector("#protocolPieChart"), protocolOptions).render();
    } else {
        document.getElementById('protocolPieChart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-400"><p>No protocol data available</p></div>';
    }

    // Traffic Time Series - Modern Area Chart
    const timeSeriesData = @json($trafficTimeSeries ?? ['labels' => [], 'bytes' => [], 'packets' => []]);
    if (timeSeriesData.labels && timeSeriesData.labels.length > 0) {
        const timeOptions = {
            chart: {
                type: 'area',
                height: 350,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            series: [
                {
                    name: 'Traffic (Bytes)',
                    data: timeSeriesData.bytes
                },
                {
                    name: 'Packets',
                    data: timeSeriesData.packets || []
                }
            ],
            xaxis: {
                categories: timeSeriesData.labels,
                labels: {
                    style: {
                        colors: '#6b7280',
                        fontSize: '11px'
                    },
                    rotate: -45,
                    rotateAlways: false
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: [
                {
                    title: {
                        text: 'Traffic',
                        style: {
                            color: '#3B82F6',
                            fontSize: '12px',
                            fontWeight: 600
                        }
                    },
                    labels: {
                        formatter: function(val) {
                            return formatBytesModern(val);
                        },
                        style: {
                            colors: '#3B82F6',
                            fontSize: '11px'
                        }
                    }
                },
                {
                    opposite: true,
                    title: {
                        text: 'Packets',
                        style: {
                            color: '#10B981',
                            fontSize: '12px',
                            fontWeight: 600
                        }
                    },
                    labels: {
                        formatter: function(val) {
                            if (val >= 1000000) return (val / 1000000).toFixed(1) + 'M';
                            if (val >= 1000) return (val / 1000).toFixed(1) + 'K';
                            return val;
                        },
                        style: {
                            colors: '#10B981',
                            fontSize: '11px'
                        }
                    }
                }
            ],
            colors: ['#3B82F6', '#10B981'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.6,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            stroke: {
                curve: 'smooth',
                width: [3, 2]
            },
            markers: {
                size: 0,
                hover: {
                    size: 6
                }
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function(val, { seriesIndex }) {
                        if (seriesIndex === 0) return formatBytesModern(val);
                        if (val >= 1000000) return (val / 1000000).toFixed(2) + 'M packets';
                        if (val >= 1000) return (val / 1000).toFixed(2) + 'K packets';
                        return val + ' packets';
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '12px',
                fontWeight: 500,
                markers: {
                    width: 12,
                    height: 12,
                    radius: 3
                }
            },
            grid: {
                borderColor: '#e5e7eb',
                strokeDashArray: 4,
                padding: {
                    left: 10,
                    right: 10
                }
            }
        };
        new ApexCharts(document.querySelector("#trafficTimeChart"), timeOptions).render();
    } else {
        document.getElementById('trafficTimeChart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-400"><svg class="w-12 h-12 mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg><p>No traffic data available for this time range</p></div>';
    }

    // Modern formatBytes function - never shows KB, always MB or GB
    function formatBytesModern(bytes) {
        if (bytes === 0 || bytes === null || bytes === undefined) return '0 MB';
        if (bytes >= 1073741824) {
            return (bytes / 1073741824).toFixed(2) + ' GB';
        }
        // Always show MB, never KB
        return (bytes / 1048576).toFixed(2) + ' MB';
    }
});
</script>
@endpush
