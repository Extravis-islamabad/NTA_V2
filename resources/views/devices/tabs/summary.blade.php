<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gray-50 rounded-lg p-4">
        <p class="text-sm text-gray-500">Total Flows</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($summaryData['total_flows']) }}</p>
    </div>
    <div class="bg-gray-50 rounded-lg p-4">
        <p class="text-sm text-gray-500">Total Bytes</p>
        <p class="text-2xl font-bold text-gray-900">
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
    <div class="bg-gray-50 rounded-lg p-4">
        <p class="text-sm text-gray-500">Total Packets</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($summaryData['total_packets']) }}</p>
    </div>
    <div class="bg-gray-50 rounded-lg p-4">
        <p class="text-sm text-gray-500">Avg Bandwidth</p>
        <p class="text-2xl font-bold text-gray-900">
            @php
                $bytes = $summaryData['avg_bandwidth'];
                if ($bytes >= 1048576) {
                    echo round($bytes / 1048576, 2) . ' MB';
                } else {
                    echo round($bytes / 1024, 2) . ' KB';
                }
            @endphp
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Applications Chart -->
    <div>
        <h3 class="text-lg font-semibold mb-4">Traffic by Application</h3>
        <div style="position: relative; height: 300px;">
            <canvas id="summaryAppChart"></canvas>
        </div>
    </div>

    <!-- Top Protocols Chart -->
    <div>
        <h3 class="text-lg font-semibold mb-4">Traffic by Protocol</h3>
        <div style="position: relative; height: 300px;">
            <canvas id="summaryProtocolChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Use a flag to ensure charts are only created once
if (typeof window.summaryChartsInitialized === 'undefined') {
    window.summaryChartsInitialized = true;

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js not loaded');
            return;
        }

        // Destroy existing chart instances if they exist
        const appCanvas = document.getElementById('summaryAppChart');
        const protocolCanvas = document.getElementById('summaryProtocolChart');

        if (!appCanvas || !protocolCanvas) {
            console.error('Chart canvases not found');
            return;
        }

        // Get existing chart instances and destroy them
        if (window.summaryAppChartInstance) {
            window.summaryAppChartInstance.destroy();
        }
        if (window.summaryProtocolChartInstance) {
            window.summaryProtocolChartInstance.destroy();
        }

        // Application Chart
        const appData = @json($trafficByApp);
        if (appData && appData.length > 0) {
            window.summaryAppChartInstance = new Chart(appCanvas, {
                type: 'doughnut',
                data: {
                    labels: appData.map(item => item.application || 'Unknown'),
                    datasets: [{
                        data: appData.map(item => item.total_bytes),
                        backgroundColor: [
                            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', 
                            '#EC4899', '#14B8A6', '#F97316', '#06B6D4', '#84CC16'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false, // Disable animation to prevent redraw issues
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 10
                            }
                        }
                    }
                }
            });
        } else {
            appCanvas.parentElement.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500">No application data available</p></div>';
        }

        // Protocol Chart
        const protocolData = @json($trafficByProtocol);
        if (protocolData && protocolData.length > 0) {
            window.summaryProtocolChartInstance = new Chart(protocolCanvas, {
                type: 'bar',
                data: {
                    labels: protocolData.map(item => item.protocol),
                    datasets: [{
                        label: 'Bytes',
                        data: protocolData.map(item => item.total_bytes),
                        backgroundColor: '#3B82F6'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false, // Disable animation
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
            protocolCanvas.parentElement.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500">No protocol data available</p></div>';
        }
    });
}
</script>
@endpush