@php
    use App\Models\Flow;
    $timeStart = match($timeRange ?? '1hour') {
        '1hour' => now()->subHour(),
        '6hours' => now()->subHours(6),
        '24hours' => now()->subDay(),
        '7days' => now()->subDays(7),
        default => now()->subHour(),
    };

    // Get interface traffic from flows
    $interfaceTraffic = Flow::where('device_id', $device->id)
        ->where('created_at', '>=', $timeStart)
        ->whereNotNull('input_interface')
        ->select('input_interface')
        ->selectRaw('SUM(bytes) as in_bytes, SUM(packets) as in_packets, COUNT(*) as flow_count')
        ->groupBy('input_interface')
        ->get()
        ->keyBy('input_interface');

    $outputTraffic = Flow::where('device_id', $device->id)
        ->where('created_at', '>=', $timeStart)
        ->whereNotNull('output_interface')
        ->select('output_interface')
        ->selectRaw('SUM(bytes) as out_bytes, SUM(packets) as out_packets')
        ->groupBy('output_interface')
        ->get()
        ->keyBy('output_interface');
@endphp

<div class="space-y-4">
    @if($device->interfaces->isEmpty() && $interfaceTraffic->isEmpty())
        <!-- Empty State with helpful message -->
        <div class="glass-card rounded-xl p-8 text-center border border-white/10">
            <div class="w-16 h-16 mx-auto bg-[#E4F2FF] rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">No Interface Data Available</h3>
            <p class="text-gray-300 mb-4">Interface data will appear once NetFlow data with interface information is received.</p>
            <div class="bg-purple-500/10 rounded-lg p-4 text-left max-w-md mx-auto border border-purple-500/30">
                <p class="text-sm text-purple-300 font-medium mb-2">To enable interface tracking:</p>
                <ul class="text-sm text-gray-300 space-y-1">
                    <li class="flex items-start gap-2">
                        <span class="text-purple-400">•</span>
                        Ensure NetFlow v9 or IPFIX is configured
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-purple-400">•</span>
                        Enable input/output interface fields
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-purple-400">•</span>
                        Configure SNMP for interface names (optional)
                    </li>
                </ul>
            </div>
        </div>
    @else
        <!-- Interface Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="glass-card rounded-xl p-4 border border-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Total Interfaces</p>
                        <p class="text-2xl font-bold text-[#5548F5]">{{ max($device->interfaces->count(), $interfaceTraffic->count()) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-[#E4F2FF] rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="glass-card rounded-xl p-4 border border-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Active (with traffic)</p>
                        <p class="text-2xl font-bold text-[#C843F3]">{{ $interfaceTraffic->count() }}</p>
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
                                $totalBytes = $interfaceTraffic->sum('in_bytes');
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

        <!-- Interface List from DB -->
        @forelse($device->interfaces as $interface)
        <div class="glass-card rounded-xl p-5 hover:bg-white/5 transition border-l-4 border-[#5548F5]">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h4 class="text-base font-bold text-white">{{ $interface->name }}</h4>
                    @if($interface->description)
                    <p class="text-sm text-gray-400">{{ $interface->description }}</p>
                    @endif
                </div>
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $interface->status === 'up' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                    {{ ucfirst($interface->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                <div class="bg-white/5 rounded-lg p-2.5">
                    <p class="text-xs text-gray-400">Type</p>
                    <p class="font-medium text-white">{{ ucfirst($interface->type ?? 'Unknown') }}</p>
                </div>
                <div class="bg-white/5 rounded-lg p-2.5">
                    <p class="text-xs text-gray-400">Speed</p>
                    <p class="font-medium text-white">{{ $interface->formatted_speed ?? 'N/A' }}</p>
                </div>
                <div class="bg-[#E4F2FF] rounded-lg p-2.5">
                    <p class="text-xs text-[#5548F5]">In Traffic</p>
                    <p class="font-medium text-[#5548F5]">{{ number_format($interface->in_octets ?? 0) }}</p>
                </div>
                <div class="bg-[#F2C7FF] rounded-lg p-2.5">
                    <p class="text-xs text-[#9619B5]">Out Traffic</p>
                    <p class="font-medium text-[#9619B5]">{{ number_format($interface->out_octets ?? 0) }}</p>
                </div>
            </div>

            @if(isset($interface->utilization_percent))
            <div class="mt-3">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-xs text-gray-300">Utilization</span>
                    <span class="text-xs font-semibold text-white">{{ number_format($interface->utilization_percent, 1) }}%</span>
                </div>
                <div class="w-full bg-white/10 rounded-full h-2">
                    <div class="h-2 rounded-full bg-gradient-to-r from-[#5548F5] to-[#C843F3]"
                         style="width: {{ min(100, $interface->utilization_percent) }}%"></div>
                </div>
            </div>
            @endif
        </div>
        @empty
            <!-- Show interface data from flows if no configured interfaces -->
            @if($interfaceTraffic->isNotEmpty())
            <div class="glass-card rounded-xl overflow-hidden border border-white/10">
                <div class="px-5 py-3 bg-purple-500/10">
                    <h3 class="text-sm font-bold text-white">Active Interfaces (from NetFlow)</h3>
                </div>
                <div class="divide-y divide-white/5">
                    @foreach($interfaceTraffic->sortByDesc('in_bytes')->take(10) as $ifIndex => $traffic)
                    <div class="p-4 hover:bg-white/5 transition">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-[#E4F2FF] rounded-lg flex items-center justify-center">
                                    <span class="text-xs font-bold text-[#5548F5]">{{ $ifIndex }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-white">Interface {{ $ifIndex }}</p>
                                    <p class="text-xs text-gray-400">{{ number_format($traffic->flow_count) }} flows</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-[#5548F5]">
                                    @php
                                        $bytes = $traffic->in_bytes;
                                        if ($bytes >= 1073741824) {
                                            echo round($bytes / 1073741824, 2) . ' GB';
                                        } elseif ($bytes >= 1048576) {
                                            echo round($bytes / 1048576, 2) . ' MB';
                                        } else {
                                            echo round($bytes / 1024, 2) . ' KB';
                                        }
                                    @endphp
                                </p>
                                <p class="text-xs text-gray-400">{{ number_format($traffic->in_packets) }} packets</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforelse
    @endif
</div>
