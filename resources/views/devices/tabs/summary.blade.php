<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="stat-card rounded-xl p-5 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400 font-medium">Total Flows</p>
                <p class="text-2xl font-bold text-white mt-1">{{ number_format($summaryData['total_flows']) }}</p>
            </div>
            <div class="w-10 h-10 gradient-primary rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="stat-card rounded-xl p-5 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Bytes</p>
                <p class="text-2xl font-bold text-[#5548F5] mt-1">
                    @php
                        $bytes = $summaryData['total_bytes'];
                        if ($bytes >= 1073741824) {
                            echo round($bytes / 1073741824, 2) . ' GB';
                        } elseif ($bytes >= 1048576) {
                            echo round($bytes / 1048576, 2) . ' MB';
                        } else {
                            echo round($bytes / 1024, 2) . ' KB';
                        }
                    @endphp
                </p>
            </div>
            <div class="w-10 h-10 bg-[#E4F2FF] rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="stat-card rounded-xl p-5 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Packets</p>
                <p class="text-2xl font-bold text-[#C843F3] mt-1">{{ number_format($summaryData['total_packets']) }}</p>
            </div>
            <div class="w-10 h-10 bg-[#F2C7FF] rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-[#C843F3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="stat-card rounded-xl p-5 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400 font-medium">Avg Bandwidth</p>
                <p class="text-2xl font-bold text-[#9619B5] mt-1">
                    @php
                        $bytes = $summaryData['avg_bandwidth'];
                        if ($bytes >= 1048576) {
                            echo round($bytes / 1048576, 2) . ' MB/s';
                        } else {
                            echo round($bytes / 1024, 2) . ' KB/s';
                        }
                    @endphp
                </p>
            </div>
            <div class="w-10 h-10 gradient-secondary rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Applications Donut Chart -->
    <div class="glass-card rounded-xl border border-white/10 p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            Traffic by Application
        </h3>
        <div id="summaryAppChart" style="height: 300px;"></div>
    </div>

    <!-- Top Protocols Bar Chart -->
    <div class="glass-card rounded-xl border border-white/10 p-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#C843F3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Traffic by Protocol
        </h3>
        <div id="summaryProtocolChart" style="height: 300px;"></div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') return;

    // Application Donut Chart
    const appData = @json($trafficByApp);
    if (appData && appData.length > 0) {
        const appOptions = {
            chart: {
                type: 'donut',
                height: 300,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif'
            },
            series: appData.map(item => parseInt(item.total_bytes)),
            labels: appData.map(item => item.application || 'Unknown'),
            colors: [
                window.monetxColors.primary,
                window.monetxColors.secondary,
                window.monetxColors.tertiary,
                window.monetxColors.success,
                window.monetxColors.warning,
                window.monetxColors.info,
                window.monetxColors.danger,
                '#14B8A6',
                '#F97316',
                '#84CC16'
            ],
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
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
                                    return formatBytes(parseInt(val));
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function(w) {
                                    return formatBytes(w.globals.seriesTotals.reduce((a, b) => a + b, 0));
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                fontSize: '11px'
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return formatBytes(val);
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#summaryAppChart"), appOptions).render();
    } else {
        document.getElementById('summaryAppChart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-400">No application data available</div>';
    }

    // Protocol Bar Chart
    const protocolData = @json($trafficByProtocol);
    if (protocolData && protocolData.length > 0) {
        const protocolOptions = {
            chart: {
                type: 'bar',
                height: 300,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
                toolbar: { show: true }
            },
            series: [{
                name: 'Traffic',
                data: protocolData.map(item => parseInt(item.total_bytes))
            }],
            xaxis: {
                categories: protocolData.map(item => item.protocol),
                labels: {
                    style: {
                        colors: '#6b7280',
                        fontSize: '11px'
                    }
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
                    borderRadius: 4,
                    columnWidth: '60%'
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return formatBytes(val);
                },
                style: {
                    fontSize: '11px',
                    fontWeight: 600
                }
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
        new ApexCharts(document.querySelector("#summaryProtocolChart"), protocolOptions).render();
    } else {
        document.getElementById('summaryProtocolChart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-400">No protocol data available</div>';
    }
});
</script>
@endpush
