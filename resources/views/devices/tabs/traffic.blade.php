<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
        <h3 class="text-lg font-semibold mb-4">Traffic Distribution</h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm text-gray-600">Inbound Traffic</span>
                    <span class="text-sm font-medium">
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
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $trafficDistribution['inbound_percent'] ?? 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm text-gray-600">Outbound Traffic</span>
                    <span class="text-sm font-medium">
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
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $trafficDistribution['outbound_percent'] ?? 0 }}%"></div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <h4 class="text-md font-semibold mb-3">Top Protocols</h4>
            <div class="space-y-2">
                @forelse($trafficByProtocol->take(5) as $protocol)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="font-medium">{{ $protocol->protocol }}</span>
                    <span class="text-sm text-gray-600">
                        @php
                            $bytes = $protocol->total_bytes;
                            if ($bytes >= 1073741824) {
                                echo round($bytes / 1073741824, 2) . ' GB';
                            } elseif ($bytes >= 1048576) {
                                echo round($bytes / 1048576, 2) . ' MB';
                            } elseif ($bytes >= 1024) {
                                echo round($bytes / 1024, 2) . ' KB';
                            } else {
                                echo $bytes . ' B';
                            }
                        @endphp
                    </span>
                </div>
                @empty
                <p class="text-center text-gray-500 py-4">No protocol data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <div>
        <h3 class="text-lg font-semibold mb-4">Traffic Over Time</h3>
        <div style="height: 300px;">
            <canvas id="trafficTimeChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') return;

    const ctx = document.getElementById('trafficTimeChart');
    if (!ctx) return;

    // Destroy existing chart if it exists
    if (window.trafficTimeChartInstance) {
        window.trafficTimeChartInstance.destroy();
    }

    // Use real data from API
    const timeSeriesData = @json($trafficTimeSeries ?? ['labels' => [], 'bytes' => []]);

    if (timeSeriesData.labels && timeSeriesData.labels.length > 0) {
        window.trafficTimeChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: timeSeriesData.labels,
                datasets: [{
                    label: 'Traffic',
                    data: timeSeriesData.bytes,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1073741824) {
                                    return (value / 1073741824).toFixed(1) + ' GB';
                                } else if (value >= 1048576) {
                                    return (value / 1048576).toFixed(1) + ' MB';
                                } else if (value >= 1024) {
                                    return (value / 1024).toFixed(1) + ' KB';
                                }
                                return value + ' B';
                            }
                        }
                    }
                }
            }
        });
    } else {
        ctx.parentElement.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500">No traffic data available for this time range</p></div>';
    }
});
</script>
@endpush
