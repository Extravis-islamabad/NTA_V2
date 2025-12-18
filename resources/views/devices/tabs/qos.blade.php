@php
    $totalBytes = $qosData->sum('total_bytes');

    // DSCP to name mapping
    $dscpNames = [
        0 => 'Best Effort (BE)',
        8 => 'CS1 - Scavenger',
        10 => 'AF11 - High-Throughput',
        12 => 'AF12',
        14 => 'AF13',
        16 => 'CS2 - OAM',
        18 => 'AF21 - Low-Latency',
        20 => 'AF22',
        22 => 'AF23',
        24 => 'CS3 - Broadcast Video',
        26 => 'AF31 - Multimedia Streaming',
        28 => 'AF32',
        30 => 'AF33',
        32 => 'CS4 - Real-Time Interactive',
        34 => 'AF41 - Multimedia Conferencing',
        36 => 'AF42',
        38 => 'AF43',
        40 => 'CS5 - Signaling',
        46 => 'EF - Expedited Forwarding',
        48 => 'CS6 - Network Control',
        56 => 'CS7 - Reserved',
    ];
@endphp

<div class="space-y-4">
    @if($qosData->isEmpty())
        <!-- Empty State -->
        <div class="glass-card rounded-xl p-8 text-center border border-white/10">
            <div class="w-16 h-16 mx-auto bg-[#F2C7FF] rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-[#9619B5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">No QoS Data Available</h3>
            <p class="text-gray-300 mb-4">DSCP/QoS information will appear once flows with QoS markings are received.</p>
            <div class="bg-purple-500/10 rounded-lg p-4 text-left max-w-md mx-auto border border-purple-500/30">
                <p class="text-sm text-purple-300 font-medium mb-2">To see QoS data:</p>
                <ul class="text-sm text-gray-300 space-y-1">
                    <li class="flex items-start gap-2">
                        <span class="text-purple-400">•</span>
                        Ensure DSCP/ToS field is included in NetFlow export
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-purple-400">•</span>
                        Traffic must have QoS markings applied
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-purple-400">•</span>
                        Wait for new flows to be collected
                    </li>
                </ul>
            </div>
        </div>
    @else
        <!-- QoS Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="glass-card rounded-xl p-4 border border-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium">QoS Classes</p>
                        <p class="text-2xl font-bold text-[#5548F5]">{{ $qosData->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-[#E4F2FF] rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="glass-card rounded-xl p-4 border border-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Total Flows</p>
                        <p class="text-2xl font-bold text-[#C843F3]">{{ number_format($qosData->sum('flow_count')) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-[#F2C7FF] rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#C843F3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="glass-card rounded-xl p-4 border border-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Total Traffic</p>
                        <p class="text-2xl font-bold text-[#9619B5]">
                            @php
                                if ($totalBytes >= 1073741824) {
                                    echo round($totalBytes / 1073741824, 2) . ' GB';
                                } elseif ($totalBytes >= 1048576) {
                                    echo round($totalBytes / 1048576, 2) . ' MB';
                                } else {
                                    echo round($totalBytes / 1024, 2) . ' KB';
                                }
                            @endphp
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-[#F2C7FF] rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#9619B5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- QoS Chart -->
        <div class="glass-card rounded-xl overflow-hidden border border-white/10">
            <div class="px-5 py-3 border-b border-white/10 flex justify-between items-center">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#9619B5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    QoS Distribution
                </h3>
            </div>
            <div class="p-4">
                <div id="qosDistributionChart" style="height: 180px;"></div>
            </div>
        </div>

        <!-- QoS Table -->
        <div class="glass-card rounded-xl overflow-hidden border border-white/10">
            <div class="px-5 py-3 bg-purple-500/10">
                <h3 class="text-sm font-bold text-white">DSCP Classification Details</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/5">
                    <thead class="bg-purple-500/10">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-purple-300 uppercase">DSCP</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-purple-300 uppercase">Class Name</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-purple-300 uppercase">Flows</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-purple-300 uppercase">Traffic</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-purple-300 uppercase">Distribution</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($qosData as $index => $qos)
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-purple-500/20 text-purple-300 border border-purple-500/30">
                                    {{ $qos->dscp }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="font-medium text-white">{{ $dscpNames[$qos->dscp] ?? 'DSCP ' . $qos->dscp }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-right text-sm text-gray-300">
                                {{ number_format($qos->flow_count) }}
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-right text-sm font-medium text-white">
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
                            <td class="px-5 py-3 whitespace-nowrap text-right">
                                @php $percent = $totalBytes > 0 ? ($qos->total_bytes / $totalBytes) * 100 : 0; @endphp
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-20 bg-white/10 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-gradient-to-r from-[#5548F5] to-[#C843F3]" style="width: {{ min($percent, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-300 w-12 text-right">{{ number_format($percent, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') return;

    const qosData = @json($qosData);
    if (qosData.length > 0) {
        const options = {
            chart: {
                type: 'donut',
                height: 180,
                fontFamily: 'Figtree, ui-sans-serif, system-ui, sans-serif'
            },
            series: qosData.map(item => parseInt(item.total_bytes)),
            labels: qosData.map(item => 'DSCP ' + item.dscp),
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
                position: 'right',
                fontSize: '10px'
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return formatBytes(val);
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#qosDistributionChart"), options).render();
    }
});
</script>
@endpush
