<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Traffic Distribution -->
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
            </svg>
            Traffic Distribution
        </h3>
        <div id="trafficDistributionChart" style="height: 200px;"></div>
        <div class="mt-4 grid grid-cols-2 gap-4">
            <div class="bg-gradient-to-r from-[#E4F2FF] to-[#F2C7FF] rounded-lg p-4">
                <p class="text-xs text-gray-600 mb-1">Inbound Traffic</p>
                <p class="text-xl font-bold text-[#5548F5]">
                    @php
                        $inbound = $trafficDistribution['inbound_bytes'] ?? 0;
                        if ($inbound >= 1073741824) {
                            echo round($inbound / 1073741824, 2) . ' GB';
                        } elseif ($inbound >= 1048576) {
                            echo round($inbound / 1048576, 2) . ' MB';
                        } elseif ($inbound >= 1024) {
                            echo round($inbound / 1024, 2) . ' KB';
                        } else {
                            echo $inbound . ' B';
                        }
                    @endphp
                </p>
            </div>
            <div class="bg-gradient-to-r from-[#F2C7FF] to-[#E4F2FF] rounded-lg p-4">
                <p class="text-xs text-gray-600 mb-1">Outbound Traffic</p>
                <p class="text-xl font-bold text-[#C843F3]">
                    @php
                        $outbound = $trafficDistribution['outbound_bytes'] ?? 0;
                        if ($outbound >= 1073741824) {
                            echo round($outbound / 1073741824, 2) . ' GB';
                        } elseif ($outbound >= 1048576) {
                            echo round($outbound / 1048576, 2) . ' MB';
                        } elseif ($outbound >= 1024) {
                            echo round($outbound / 1024, 2) . ' KB';
                        } else {
                            echo $outbound . ' B';
                        }
                    @endphp
                </p>
            </div>
        </div>
    </div>

    <!-- Top Protocols -->
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#C843F3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Top Protocols
        </h3>
        <div id="protocolPieChart" style="height: 250px;"></div>
    </div>
</div>

<!-- Traffic Over Time Chart -->
<div class="mt-6 bg-white rounded-xl border border-gray-100 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
        <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
        </svg>
        Traffic Over Time
    </h3>
    <div id="trafficTimeChart" style="height: 300px;"></div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') return;

    const inbound = {{ $trafficDistribution['inbound_bytes'] ?? 0 }};
    const outbound = {{ $trafficDistribution['outbound_bytes'] ?? 0 }};

    // Traffic Distribution Donut
    if (inbound > 0 || outbound > 0) {
        const distributionOptions = {
            chart: {
                type: 'donut',
                height: 200,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif'
            },
            series: [inbound, outbound],
            labels: ['Inbound', 'Outbound'],
            colors: [window.monetxColors.primary, window.monetxColors.secondary],
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
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
            legend: {
                position: 'bottom',
                fontSize: '12px'
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return formatBytes(val);
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#trafficDistributionChart"), distributionOptions).render();
    }

    // Protocol Donut Chart - modern style
    const protocolData = @json($trafficByProtocol);
    if (protocolData && protocolData.length > 0) {
        const protocolOptions = {
            chart: {
                type: 'donut',
                height: 250,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '55%',
                        labels: {
                            show: true,
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
            series: protocolData.map(p => parseInt(p.total_bytes)),
            labels: protocolData.map(p => p.protocol),
            colors: [
                window.monetxColors.primary,
                window.monetxColors.secondary,
                window.monetxColors.tertiary,
                window.monetxColors.success,
                window.monetxColors.warning,
                window.monetxColors.info
            ],
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
        new ApexCharts(document.querySelector("#protocolPieChart"), protocolOptions).render();
    }

    // Traffic Time Series
    const timeSeriesData = @json($trafficTimeSeries ?? ['labels' => [], 'bytes' => []]);
    if (timeSeriesData.labels && timeSeriesData.labels.length > 0) {
        const timeOptions = {
            chart: {
                type: 'area',
                height: 300,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif',
                toolbar: { show: true }
            },
            series: [{
                name: 'Traffic',
                data: timeSeriesData.bytes
            }],
            xaxis: {
                categories: timeSeriesData.labels,
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
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2
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
        new ApexCharts(document.querySelector("#trafficTimeChart"), timeOptions).render();
    } else {
        document.getElementById('trafficTimeChart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">No traffic data available</div>';
    }
});
</script>
@endpush
