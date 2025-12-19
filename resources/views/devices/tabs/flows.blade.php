<!-- Sub-tabs for Flows -->
<div class="mb-6 border-b border-purple-500/20">
    <nav class="flex gap-6" x-data="{ activeSubTab: 'details' }">
        <button @click="activeSubTab = 'details'" :class="activeSubTab === 'details' ? 'border-b-2 border-purple-400 text-purple-400' : 'text-purple-300/60 hover:text-purple-300'" class="pb-2 text-sm font-medium transition-colors">
            Flow Details
        </button>
        <button @click="activeSubTab = 'conversations'" :class="activeSubTab === 'conversations' ? 'border-b-2 border-purple-400 text-purple-400' : 'text-purple-300/60 hover:text-purple-300'" class="pb-2 text-sm font-medium transition-colors">
            Conversations
        </button>
    </nav>
</div>

<div x-data="{ activeSubTab: 'details' }">
    <!-- Flow Details Table -->
    <div x-show="activeSubTab === 'details'" x-cloak>
        <div class="overflow-x-auto glass-card rounded-xl">
            <table class="min-w-full">
                <thead class="bg-purple-500/10">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Source</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Destination</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Protocol</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Application</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Traffic</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Packets</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-500/10">
                    @forelse($flowDetails ?? [] as $index => $flow)
                    <tr class="{{ $index % 2 === 0 ? 'bg-purple-500/5' : '' }} hover:bg-purple-500/10 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-purple-300/60">
                            {{ $flow->created_at->format('M d, H:i:s') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-1">
                                <span class="font-mono text-xs text-white">{{ $flow->source_ip }}</span>
                                <span class="text-purple-300/40">:</span>
                                <span class="text-xs text-purple-300/60">{{ $flow->source_port }}</span>
                                <button onclick="copyToClipboard('{{ $flow->source_ip }}')" class="p-1 hover:bg-purple-500/20 rounded opacity-0 group-hover:opacity-100 transition-opacity" title="Copy IP">
                                    <svg class="w-3 h-3 text-purple-300/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-1">
                                <span class="font-mono text-xs text-white">{{ $flow->destination_ip }}</span>
                                <span class="text-purple-300/40">:</span>
                                <span class="text-xs text-purple-300/60">{{ $flow->destination_port }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-blue-500/20 text-blue-400">
                                {{ $flow->protocol }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-purple-300/70">
                            {{ $flow->application ?? '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs font-medium text-white">
                            {{ $flow->formatted_bytes }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-purple-300/60">
                            {{ number_format($flow->packets) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center text-purple-300/50">
                                <svg class="w-12 h-12 mb-2 text-purple-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-sm">No flow data for this time range</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($flowDetails) && $flowDetails->hasPages())
        <div class="mt-4">
            {{ $flowDetails->appends(['tab' => 'flows', 'range' => $timeRange])->links() }}
        </div>
        @endif
    </div>

    <!-- Conversations Table -->
    <div x-show="activeSubTab === 'conversations'" x-cloak>
        <div class="overflow-x-auto glass-card rounded-xl">
            <table class="min-w-full">
                <thead class="bg-purple-500/10">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Source</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Destination</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Protocol</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Application</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Traffic</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Packets</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider">Flows</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-500/10">
                    @forelse($conversations ?? [] as $index => $conv)
                    <tr class="{{ $index % 2 === 0 ? 'bg-purple-500/5' : '' }} hover:bg-purple-500/10 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="font-mono text-xs text-white">{{ $conv->source_ip }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="font-mono text-xs text-white">{{ $conv->destination_ip }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $conv->protocol === 'TCP' ? 'bg-blue-500/20 text-blue-400' : ($conv->protocol === 'UDP' ? 'bg-green-500/20 text-green-400' : 'bg-purple-500/20 text-purple-400') }}">
                                {{ $conv->protocol }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($conv->application)
                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-cyan-500/20 text-cyan-400">
                                {{ $conv->application }}
                            </span>
                            @else
                            <span class="text-xs text-purple-300/40">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs font-medium text-white">
                            @php
                                $bytes = $conv->total_bytes;
                                if ($bytes >= 1073741824) {
                                    echo round($bytes / 1073741824, 2) . ' GB';
                                } elseif ($bytes >= 1048576) {
                                    echo round($bytes / 1048576, 2) . ' MB';
                                } else {
                                    echo round($bytes / 1024, 2) . ' KB';
                                }
                            @endphp
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-purple-300/60">
                            {{ number_format($conv->total_packets) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-purple-300/60">
                            {{ number_format($conv->flow_count) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center text-purple-300/50">
                                <svg class="w-12 h-12 mb-2 text-purple-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <p class="text-sm">No conversation data</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Could add a toast notification here
    });
}
</script>
@endpush
