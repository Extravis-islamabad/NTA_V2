@php
    $totalAppBytes = ($trafficByApp ?? collect())->sum('total_bytes') ?: 1;
    $totalCloudBytes = ($cloudTraffic ?? collect())->sum('bytes') ?: 1;
    $chartColors = ['#22d3ee', '#10B981', '#818cf8', '#F59E0B', '#EF4444', '#EC4899', '#3B82F6', '#84CC16', '#F97316', '#14B8A6'];
@endphp

<!-- Application Distribution Chart + Table -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Application Donut Chart -->
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="px-5 py-3 border-b border-white/10">
            <h3 class="text-base font-semibold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Application Distribution
            </h3>
        </div>
        <div class="p-4">
            @if(isset($trafficByApp) && $trafficByApp->isNotEmpty())
                <div id="appDistributionChart" style="height: 300px;"></div>
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

    <!-- Application Table -->
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="px-5 py-3 border-b border-white/10">
            <h3 class="text-base font-semibold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Top Applications
            </h3>
        </div>
        <div class="p-4 max-h-80 overflow-y-auto">
            @forelse($trafficByApp ?? [] as $index => $app)
            @php
                $percent = round(($app->total_bytes / $totalAppBytes) * 100, 1);
            @endphp
            <div class="flex items-center justify-between py-2.5 {{ $index > 0 ? 'border-t border-white/5' : '' }} hover:bg-white/5 px-2 rounded transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $chartColors[$index % count($chartColors)] }}"></div>
                    <span class="text-sm text-white font-medium">{{ $app->application }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-24 h-2 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full rounded-full" style="width: {{ $percent }}%; background-color: {{ $chartColors[$index % count($chartColors)] }}"></div>
                    </div>
                    <span class="text-xs text-gray-400 w-12 text-right">{{ $percent }}%</span>
                    <span class="text-sm font-medium text-white w-20 text-right">
                        @php
                            $bytes = $app->total_bytes;
                            echo $bytes >= 1073741824 ? round($bytes / 1073741824, 1) . ' GB' : round($bytes / 1048576, 1) . ' MB';
                        @endphp
                    </span>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-8 text-gray-500">
                <p class="text-sm">No application data</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Cloud Services Section -->
<div class="glass-card rounded-xl overflow-hidden">
    <div class="px-5 py-3 border-b border-white/10">
        <h3 class="text-base font-semibold text-white flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
            </svg>
            Cloud Services Traffic
        </h3>
    </div>
    <div class="p-4">
        @if(isset($cloudTraffic) && $cloudTraffic->isNotEmpty())
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Cloud Donut Chart -->
            <div id="cloudServicesChart" style="height: 280px;"></div>

            <!-- Cloud Services List -->
            <div class="space-y-3">
                @foreach($cloudTraffic as $index => $cloud)
                @php
                    $percent = round(($cloud['bytes'] / $totalCloudBytes) * 100, 1);
                @endphp
                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: {{ $chartColors[$index % count($chartColors)] }}20">
                            <span class="text-xs font-bold" style="color: {{ $chartColors[$index % count($chartColors)] }}">
                                {{ strtoupper(substr($cloud['provider'], 0, 2)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">{{ $cloud['provider'] }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($cloud['unique_ips']) }} unique IPs</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-white">
                            @php
                                $bytes = $cloud['bytes'];
                                echo $bytes >= 1073741824 ? round($bytes / 1073741824, 2) . ' GB' : round($bytes / 1048576, 2) . ' MB';
                            @endphp
                        </p>
                        <p class="text-xs text-gray-500">{{ $percent }}% of cloud traffic</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-12 text-gray-500">
            <svg class="w-16 h-16 mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
            </svg>
            <p class="text-sm font-medium mb-1">No Cloud Traffic Detected</p>
            <p class="text-xs text-gray-600">Cloud service traffic will appear here when detected</p>
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

    // Application Distribution Chart
    const appData = @json($trafficByApp ?? []);
    if (appData && appData.length > 0) {
        new ApexCharts(document.querySelector("#appDistributionChart"), {
            chart: { type: 'donut', height: 300, background: 'transparent' },
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

    // Cloud Services Chart
    const cloudData = @json($cloudTraffic ?? []);
    if (cloudData && cloudData.length > 0) {
        new ApexCharts(document.querySelector("#cloudServicesChart"), {
            chart: { type: 'donut', height: 280, background: 'transparent' },
            series: cloudData.map(item => parseInt(item.bytes)),
            labels: cloudData.map(item => item.provider),
            colors: chartColors,
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
                        labels: {
                            show: true,
                            name: { color: '#9ca3af' },
                            value: { color: '#fff', formatter: (val) => formatBytes(parseInt(val)) },
                            total: { show: true, label: 'Cloud Traffic', color: '#9ca3af', formatter: (w) => formatBytes(w.globals.seriesTotals.reduce((a, b) => a + b, 0)) }
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
});
</script>
@endpush
