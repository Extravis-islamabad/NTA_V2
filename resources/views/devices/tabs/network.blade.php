@php
    $interfaces = $device->interfaces ?? collect();
    $totalQosBytes = ($qosData ?? collect())->sum('total_bytes') ?: 1;
    $chartColors = ['#22d3ee', '#10B981', '#818cf8', '#F59E0B', '#EF4444', '#EC4899', '#3B82F6', '#84CC16'];

    // DSCP class names mapping
    $dscpNames = [
        0 => 'Best Effort (BE)',
        8 => 'Class Selector 1 (CS1)',
        10 => 'AF11',
        12 => 'AF12',
        14 => 'AF13',
        16 => 'Class Selector 2 (CS2)',
        18 => 'AF21',
        20 => 'AF22',
        22 => 'AF23',
        24 => 'Class Selector 3 (CS3)',
        26 => 'AF31',
        28 => 'AF32',
        30 => 'AF33',
        32 => 'Class Selector 4 (CS4)',
        34 => 'AF41',
        36 => 'AF42',
        38 => 'AF43',
        40 => 'Class Selector 5 (CS5)',
        46 => 'Expedited Forwarding (EF)',
        48 => 'Class Selector 6 (CS6)',
        56 => 'Class Selector 7 (CS7)',
    ];
@endphp

<!-- Interface Stats Section -->
<div class="mb-6">
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="px-5 py-3 border-b border-white/10">
            <h3 class="text-base font-semibold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                </svg>
                Network Interfaces
            </h3>
        </div>
        <div class="p-4">
            @if($interfaces->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Interface</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Index</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Speed</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">In Traffic</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Out Traffic</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($interfaces as $index => $interface)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white/[0.02]' : '' }} hover:bg-white/5 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-sm font-medium text-white">{{ $interface->name }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-xs text-gray-400">{{ $interface->if_index }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-xs text-gray-400">
                                    @if($interface->speed)
                                        {{ $interface->speed >= 1000 ? ($interface->speed / 1000) . ' Gbps' : $interface->speed . ' Mbps' }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($interface->admin_status === 'up' && $interface->oper_status === 'up')
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-green-500/20 text-green-400">Up</span>
                                @elseif($interface->admin_status === 'down')
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-gray-500/20 text-gray-400">Admin Down</span>
                                @else
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-red-500/20 text-red-400">Down</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-blue-400 font-medium">
                                @php
                                    $inBytes = $interface->in_octets ?? 0;
                                    echo $inBytes >= 1073741824 ? round($inBytes / 1073741824, 2) . ' GB' : round($inBytes / 1048576, 2) . ' MB';
                                @endphp
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-emerald-400 font-medium">
                                @php
                                    $outBytes = $interface->out_octets ?? 0;
                                    echo $outBytes >= 1073741824 ? round($outBytes / 1073741824, 2) . ' GB' : round($outBytes / 1048576, 2) . ' MB';
                                @endphp
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-12">
                <div class="bg-blue-500/10 rounded-xl p-6 max-w-md text-center border border-blue-500/20">
                    <svg class="w-12 h-12 mx-auto mb-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h4 class="text-sm font-semibold text-white mb-2">No Interface Data</h4>
                    <p class="text-xs text-gray-400 mb-4">Enable SNMP polling to discover network interfaces and monitor traffic statistics.</p>
                    <a href="{{ route('devices.edit', $device) }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-medium text-white bg-blue-500/20 hover:bg-blue-500/30 border border-blue-500/30 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Configure SNMP
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- QoS Distribution Section -->
<div class="glass-card rounded-xl overflow-hidden">
    <div class="px-5 py-3 border-b border-white/10">
        <h3 class="text-base font-semibold text-white flex items-center gap-2">
            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
            </svg>
            QoS / DSCP Distribution
        </h3>
    </div>
    <div class="p-4">
        @if(isset($qosData) && $qosData->isNotEmpty())
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- QoS Chart -->
            <div id="qosDistributionChart" style="height: 280px;"></div>

            <!-- QoS Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase">DSCP</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase">Class</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase">Traffic</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-400 uppercase">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($qosData as $index => $qos)
                        @php
                            $percent = round(($qos->total_bytes / $totalQosBytes) * 100, 1);
                            $dscpValue = intval($qos->dscp);
                            $dscpName = $dscpNames[$dscpValue] ?? "DSCP $dscpValue";
                        @endphp
                        <tr class="{{ $index % 2 === 0 ? 'bg-white/[0.02]' : '' }} hover:bg-white/5 transition-colors">
                            <td class="px-4 py-2 whitespace-nowrap">
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full" style="background-color: {{ $chartColors[$index % count($chartColors)] }}20; color: {{ $chartColors[$index % count($chartColors)] }}">
                                    {{ $qos->dscp }}
                                </span>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-xs text-white">
                                {{ $dscpName }}
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-xs font-medium text-white">
                                @php
                                    $bytes = $qos->total_bytes;
                                    echo $bytes >= 1073741824 ? round($bytes / 1073741824, 2) . ' GB' : round($bytes / 1048576, 2) . ' MB';
                                @endphp
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-1.5 bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full" style="width: {{ $percent }}%; background-color: {{ $chartColors[$index % count($chartColors)] }}"></div>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $percent }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-12">
            <div class="bg-amber-500/10 rounded-xl p-6 max-w-md text-center border border-amber-500/20">
                <svg class="w-12 h-12 mx-auto mb-3 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h4 class="text-sm font-semibold text-white mb-2">No QoS Data Available</h4>
                <p class="text-xs text-gray-400 mb-4">DSCP/QoS data will appear once the device sends flows with QoS markings.</p>
                <a href="{{ route('settings.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-medium text-white bg-amber-500/20 hover:bg-amber-500/30 border border-amber-500/30 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Configure Settings
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') return;

    const chartColors = @json($chartColors);

    function formatBytes(bytes) {
        if (!bytes || bytes === 0) return '0 MB';
        if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(1) + ' GB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    // QoS Distribution Chart
    const qosData = @json($qosData ?? []);
    if (qosData && qosData.length > 0) {
        new ApexCharts(document.querySelector("#qosDistributionChart"), {
            chart: { type: 'pie', height: 280, background: 'transparent' },
            series: qosData.map(item => parseInt(item.total_bytes)),
            labels: qosData.map(item => 'DSCP ' + item.dscp),
            colors: chartColors,
            stroke: { width: 2, colors: ['rgba(15, 15, 26, 0.8)'] },
            legend: { position: 'bottom', fontSize: '10px', labels: { colors: '#9ca3af' } },
            dataLabels: { enabled: false },
            tooltip: { theme: 'dark', y: { formatter: formatBytes } }
        }).render();
    }
});
</script>
@endpush
