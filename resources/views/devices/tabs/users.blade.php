@php
    use App\Models\Flow;

    // Get user-based traffic analytics by source IP
    $timeStart = match($timeRange) {
        '1hour' => now()->subHour(),
        '6hours' => now()->subHours(6),
        '24hours' => now()->subDay(),
        '7days' => now()->subDays(7),
        default => now()->subHour(),
    };

    // Get traffic by source IP (representing users)
    $userTraffic = Flow::where('device_id', $device->id)
        ->where('created_at', '>=', $timeStart)
        ->select('source_ip')
        ->selectRaw('SUM(bytes) as total_bytes')
        ->selectRaw('SUM(packets) as total_packets')
        ->selectRaw('COUNT(*) as flow_count')
        ->selectRaw('COUNT(DISTINCT destination_ip) as unique_destinations')
        ->selectRaw('COUNT(DISTINCT application) as unique_apps')
        ->selectRaw('MIN(created_at) as first_seen')
        ->selectRaw('MAX(created_at) as last_seen')
        ->groupBy('source_ip')
        ->orderByDesc('total_bytes')
        ->limit(50)
        ->get();

    $totalBytes = $userTraffic->sum('total_bytes');
    $totalUsers = $userTraffic->count();

    // Get top applications per user (for the most active user)
    $topUser = $userTraffic->first();
    $topUserApps = [];
    if ($topUser) {
        $topUserApps = Flow::where('device_id', $device->id)
            ->where('source_ip', $topUser->source_ip)
            ->where('created_at', '>=', $timeStart)
            ->whereNotNull('application')
            ->select('application')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->groupBy('application')
            ->orderByDesc('total_bytes')
            ->limit(5)
            ->get();
    }

    // Format bytes helper
    $formatBytes = function($bytes) {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    };
@endphp

<div class="space-y-6">
    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-5 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-white/80">Active Users</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-xl p-5 border border-white/10">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Total Traffic</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $formatBytes($totalBytes) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-xl p-5 border border-white/10">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Total Flows</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ number_format($userTraffic->sum('flow_count')) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-xl p-5 border border-white/10">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Avg per User</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $totalUsers > 0 ? $formatBytes($totalBytes / $totalUsers) : '0 B' }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @if($userTraffic->isEmpty())
        <div class="glass-card rounded-xl p-12 text-center border border-white/10">
            <div class="w-20 h-20 mx-auto bg-white/5 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">No User Activity</h3>
            <p class="text-gray-400">No user traffic data available for the selected time range.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Traffic Table -->
            <div class="lg:col-span-2 glass-card rounded-xl overflow-hidden border border-white/10">
                <div class="px-6 py-4 border-b border-white/10 bg-purple-500/10">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        User Traffic Analysis
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/5">
                        <thead class="bg-purple-500/10">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-purple-300 uppercase tracking-wider">User IP</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-purple-300 uppercase tracking-wider">Traffic</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-purple-300 uppercase tracking-wider">Flows</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-purple-300 uppercase tracking-wider">Destinations</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-purple-300 uppercase tracking-wider">Activity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($userTraffic as $index => $user)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold"
                                             style="background: linear-gradient(135deg, {{ ['#5548F5', '#C843F3', '#10B981', '#F59E0B', '#EF4444', '#3B82F6', '#8B5CF6', '#EC4899', '#14B8A6', '#84CC16'][$index % 10] }}, {{ ['#C843F3', '#9619B5', '#059669', '#D97706', '#DC2626', '#2563EB', '#7C3AED', '#DB2777', '#0D9488', '#65A30D'][$index % 10] }});">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <code class="font-mono text-sm text-white">{{ $user->source_ip }}</code>
                                            <p class="text-xs text-gray-400">{{ $user->unique_apps }} apps used</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="font-semibold text-white">{{ $formatBytes($user->total_bytes) }}</span>
                                    <div class="w-24 h-1.5 bg-white/10 rounded-full mt-1 ml-auto">
                                        @php $percent = $totalBytes > 0 ? ($user->total_bytes / $totalBytes) * 100 : 0; @endphp
                                        <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-purple-600" style="width: {{ min($percent, 100) }}%"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-300">
                                    {{ number_format($user->flow_count) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-300">
                                    {{ number_format($user->unique_destinations) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-400">
                                        <span class="block">First: {{ \Carbon\Carbon::parse($user->first_seen)->format('H:i') }}</span>
                                        <span class="block">Last: {{ \Carbon\Carbon::parse($user->last_seen)->format('H:i') }}</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top User Details -->
            <div class="space-y-6">
                @if($topUser)
                <div class="glass-card rounded-xl overflow-hidden border border-white/10">
                    <div class="px-6 py-4 border-b border-white/10 bg-purple-500/10">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            Top User
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold mb-3">
                                1
                            </div>
                            <code class="text-lg font-mono font-semibold text-white">{{ $topUser->source_ip }}</code>
                            <p class="text-2xl font-bold text-purple-400 mt-2">{{ $formatBytes($topUser->total_bytes) }}</p>
                            <p class="text-sm text-gray-400">{{ number_format($topUser->flow_count) }} flows</p>
                        </div>

                        @if($topUserApps->isNotEmpty())
                        <div class="border-t border-white/10 pt-4 mt-4">
                            <h4 class="text-sm font-semibold text-gray-200 mb-3">Top Applications</h4>
                            <div class="space-y-2">
                                @foreach($topUserApps as $app)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-300">{{ $app->application }}</span>
                                    <span class="font-medium text-white">{{ $formatBytes($app->total_bytes) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Info Card -->
                <div class="bg-purple-500/10 rounded-xl p-6 border border-purple-500/30">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white">User Identification</h4>
                            <p class="text-sm text-gray-300 mt-1">
                                Users are identified by their source IP address. For enhanced user identification, consider integrating with Active Directory or LDAP.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
