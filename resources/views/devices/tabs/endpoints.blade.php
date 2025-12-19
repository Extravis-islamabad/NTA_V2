<!-- Sub-tabs for Endpoints -->
<div class="mb-6 border-b border-white/10" x-data="{ activeSubTab: 'sources' }">
    <nav class="flex gap-6">
        <button @click="activeSubTab = 'sources'" :class="activeSubTab === 'sources' ? 'border-b-2 border-blue-400 text-blue-400' : 'text-gray-400 hover:text-white'" class="pb-2 text-sm font-medium transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
            </svg>
            Sources
        </button>
        <button @click="activeSubTab = 'destinations'" :class="activeSubTab === 'destinations' ? 'border-b-2 border-emerald-400 text-emerald-400' : 'text-gray-400 hover:text-white'" class="pb-2 text-sm font-medium transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
            Destinations
        </button>
        <button @click="activeSubTab = 'as'" :class="activeSubTab === 'as' ? 'border-b-2 border-cyan-400 text-cyan-400' : 'text-gray-400 hover:text-white'" class="pb-2 text-sm font-medium transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
            </svg>
            AS View
        </button>
    </nav>

    @php
        $totalSourceBytes = ($topSources ?? collect())->sum('total_bytes') ?: 1;
        $totalDestBytes = ($topDestinations ?? collect())->sum('total_bytes') ?: 1;
    @endphp

    <!-- Sources Table -->
    <div x-show="activeSubTab === 'sources'" x-cloak class="mt-6">
        <div class="glass-card rounded-xl overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-blue-500/10">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Source IP</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Traffic</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-1/3">Distribution</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Flows</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($topSources ?? [] as $index => $source)
                    @php
                        $percent = round(($source->total_bytes / $totalSourceBytes) * 100, 1);
                    @endphp
                    <tr class="{{ $index % 2 === 0 ? 'bg-white/[0.02]' : '' }} hover:bg-white/5 transition-colors group">
                        <td class="px-5 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-sm text-white">{{ $source->source_ip }}</span>
                                <button onclick="copyToClipboard('{{ $source->source_ip }}')" class="p-1 hover:bg-blue-500/20 rounded opacity-0 group-hover:opacity-100 transition-opacity" title="Copy">
                                    <svg class="w-3.5 h-3.5 text-blue-400/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-sm font-medium text-white">
                            @php
                                $bytes = $source->total_bytes;
                                echo $bytes >= 1073741824 ? round($bytes / 1073741824, 2) . ' GB' : round($bytes / 1048576, 2) . ' MB';
                            @endphp
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-2 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-blue-500 to-blue-400 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="text-xs text-blue-400 font-medium w-12 text-right">{{ $percent }}%</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-400">
                            {{ number_format($source->flow_count) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center text-gray-500">
                                <svg class="w-12 h-12 mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                                </svg>
                                <p class="text-sm">No source data available</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Destinations Table -->
    <div x-show="activeSubTab === 'destinations'" x-cloak class="mt-6">
        <div class="glass-card rounded-xl overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-emerald-500/10">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Destination IP</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Traffic</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-1/3">Distribution</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Flows</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($topDestinations ?? [] as $index => $dest)
                    @php
                        $percent = round(($dest->total_bytes / $totalDestBytes) * 100, 1);
                    @endphp
                    <tr class="{{ $index % 2 === 0 ? 'bg-white/[0.02]' : '' }} hover:bg-white/5 transition-colors group">
                        <td class="px-5 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-sm text-white">{{ $dest->destination_ip }}</span>
                                <button onclick="copyToClipboard('{{ $dest->destination_ip }}')" class="p-1 hover:bg-emerald-500/20 rounded opacity-0 group-hover:opacity-100 transition-opacity" title="Copy">
                                    <svg class="w-3.5 h-3.5 text-emerald-400/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-sm font-medium text-white">
                            @php
                                $bytes = $dest->total_bytes;
                                echo $bytes >= 1073741824 ? round($bytes / 1073741824, 2) . ' GB' : round($bytes / 1048576, 2) . ' MB';
                            @endphp
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-2 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="text-xs text-emerald-400 font-medium w-12 text-right">{{ $percent }}%</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-400">
                            {{ number_format($dest->flow_count) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center text-gray-500">
                                <svg class="w-12 h-12 mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                                <p class="text-sm">No destination data available</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- AS View Table -->
    <div x-show="activeSubTab === 'as'" x-cloak class="mt-6">
        <div class="glass-card rounded-xl overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-cyan-500/10">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">AS Number</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Organization</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Country</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Sent</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Received</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Flows</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($asTraffic ?? [] as $index => $as)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white/[0.02]' : '' }} hover:bg-white/5 transition-colors">
                        <td class="px-5 py-3 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-cyan-500/20 text-cyan-300">
                                AS{{ $as['asn'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-sm text-white max-w-xs truncate" title="{{ $as['name'] }}">
                            {{ $as['name'] ?: 'Unknown' }}
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            @if($as['country'])
                            <span class="px-2 py-0.5 text-[10px] font-medium rounded bg-blue-500/20 text-blue-300">
                                {{ $as['country'] }}
                            </span>
                            @else
                            <span class="text-xs text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-sm text-blue-400 font-medium">
                            @php
                                $sent = $as['bytes_sent'];
                                echo $sent >= 1073741824 ? round($sent / 1073741824, 2) . ' GB' : round($sent / 1048576, 2) . ' MB';
                            @endphp
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-sm text-emerald-400 font-medium">
                            @php
                                $recv = $as['bytes_received'];
                                echo $recv >= 1073741824 ? round($recv / 1073741824, 2) . ' GB' : round($recv / 1048576, 2) . ' MB';
                            @endphp
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-400">
                            {{ number_format($as['flows']) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center text-gray-500">
                                <svg class="w-12 h-12 mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                <p class="text-sm">No AS data available</p>
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
        // Success
    });
}
</script>
@endpush
