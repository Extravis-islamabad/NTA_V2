@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Devices</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_devices'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Online</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['online_devices'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Offline</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['offline_devices'] }}</p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Flows</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_flows']) }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active Alarms</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $stats['active_alarms'] }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Applications Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Top Applications</h3>
                <select id="appTimeRange" onchange="updateApplicationChart()" class="text-sm border-gray-300 rounded-md">
                    <option value="1hour">Last Hour</option>
                    <option value="6hours">Last 6 Hours</option>
                    <option value="24hours">Last 24 Hours</option>
                </select>
            </div>
            <div style="position: relative; height: 300px;">
                <canvas id="applicationsChart"></canvas>
            </div>
        </div>


        <!-- Top Protocols Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Top Protocols</h3>
                <select id="protocolTimeRange" onchange="updateProtocolChart()" class="text-sm border-gray-300 rounded-md">
                    <option value="1hour">Last Hour</option>
                    <option value="6hours">Last 6 Hours</option>
                    <option value="24hours">Last 24 Hours</option>
                </select>
            </div>
            <div style="position: relative; height: 300px;">
                <canvas id="protocolsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Advanced Widgets Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- HeatMap -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">HeatMap</h3>
            <div class="flex items-center justify-center">
                <div class="relative" style="width: 200px; height: 200px;">
                    <svg viewBox="0 0 200 200" class="transform -rotate-90">
                        <!-- Background circle -->
                        <circle cx="100" cy="100" r="80" fill="none" stroke="#e5e7eb" stroke-width="20"/>
                        
                        <!-- Link Up (Green) -->
                        @php
                            $total = max(1, $heatMapData['link_up'] + $heatMapData['link_down'] + $heatMapData['unknown']);
                            $linkUpPercent = ($heatMapData['link_up'] / $total) * 100;
                            $linkDownPercent = ($heatMapData['link_down'] / $total) * 100;
                            $unknownPercent = ($heatMapData['unknown'] / $total) * 100;
                            
                            $circumference = 2 * pi() * 80;
                            $linkUpDash = ($linkUpPercent / 100) * $circumference;
                            $linkDownDash = ($linkDownPercent / 100) * $circumference;
                            $unknownDash = ($unknownPercent / 100) * $circumference;
                            
                            $offset = 0;
                        @endphp
                        
                        <circle cx="100" cy="100" r="80" fill="none" 
                                stroke="#10B981" stroke-width="20"
                                stroke-dasharray="{{ $linkUpDash }} {{ $circumference }}"
                                stroke-dashoffset="{{ $offset }}"
                                class="transition-all duration-300"/>
                        
                        <!-- Link Down (Red) -->
                        @php $offset -= $linkUpDash; @endphp
                        <circle cx="100" cy="100" r="80" fill="none" 
                                stroke="#EF4444" stroke-width="20"
                                stroke-dasharray="{{ $linkDownDash }} {{ $circumference }}"
                                stroke-dashoffset="{{ $offset }}"
                                class="transition-all duration-300"/>
                        
                        <!-- Unknown (Yellow) -->
                        @php $offset -= $linkDownDash; @endphp
                        <circle cx="100" cy="100" r="80" fill="none" 
                                stroke="#F59E0B" stroke-width="20"
                                stroke-dasharray="{{ $unknownDash }} {{ $circumference }}"
                                stroke-dashoffset="{{ $offset }}"
                                class="transition-all duration-300"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-3xl font-bold text-gray-900">{{ $stats['total_devices'] }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                        <span class="text-gray-600">Link Up</span>
                    </div>
                    <span class="font-medium">{{ $heatMapData['link_up'] }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                        <span class="text-gray-600">Link Down</span>
                    </div>
                    <span class="font-medium">{{ $heatMapData['link_down'] }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                        <span class="text-gray-600">Unknown</span>
                    </div>
                    <span class="font-medium">{{ $heatMapData['unknown'] }}</span>
                </div>
            </div>
        </div>

        <!-- Top QoS -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top N QoS</h3>
            <p class="text-xs text-gray-500 mb-4">Last hour</p>
            
            @if($topQoS->isEmpty())
                <div class="text-center py-8">
                    <p class="text-sm text-gray-500">No QoS data available</p>
                </div>
            @else
                <div style="position: relative; height: 200px; margin-bottom: 1rem;">
                    <canvas id="qosChart"></canvas>
                </div>
                <div class="space-y-1 max-h-40 overflow-y-auto">
                    @foreach($topQoS as $qos)
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full mr-2" style="background-color: {{ ['#10B981', '#F59E0B', '#EF4444', '#3B82F6', '#8B5CF6', '#EC4899', '#14B8A6', '#F97316', '#06B6D4', '#84CC16'][$loop->index % 10] }}"></div>
                            <span class="text-gray-600">{{ $qos['dscp'] }}</span>
                        </div>
                        <span class="font-medium">{{ $qos['formatted_bytes'] }}</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Top Conversations -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top N Conversation</h3>
            <p class="text-xs text-gray-500 mb-4">Last hour</p>
            
            @if($topConversations->isEmpty())
                <div class="text-center py-8">
                    <p class="text-sm text-gray-500">No conversation data available</p>
                </div>
            @else
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                    <table class="min-w-full text-xs">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-2 py-1 text-left text-xs font-medium text-gray-500">Source</th>
                                <th class="px-2 py-1 text-left text-xs font-medium text-gray-500">Destination</th>
                                <th class="px-2 py-1 text-left text-xs font-medium text-gray-500">App</th>
                                <th class="px-2 py-1 text-left text-xs font-medium text-gray-500">DSCP</th>
                                <th class="px-2 py-1 text-left text-xs font-medium text-gray-500">Traffic</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($topConversations as $conv)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-1 text-gray-900 truncate" style="max-width: 100px;" title="{{ $conv['source'] }}">
                                    {{ Str::limit($conv['source'], 12, '') }}
                                </td>
                                <td class="px-2 py-1 text-gray-900 truncate" style="max-width: 100px;" title="{{ $conv['destination'] }}">
                                    {{ Str::limit($conv['destination'], 12, '') }}
                                </td>
                                <td class="px-2 py-1 text-gray-600">{{ $conv['application'] }}</td>
                                <td class="px-2 py-1 text-gray-600">{{ $conv['dscp'] }}</td>
                                <td class="px-2 py-1 text-gray-900 font-medium">{{ $conv['formatted_bytes'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Device Summary -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Device Summary</h3>
                <button onclick="refreshDeviceTable()" class="text-sm text-indigo-600 hover:text-indigo-900">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="deviceTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interfaces</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flows</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="deviceTableBody">
                            @foreach($devices as $device)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('devices.show', $device) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                        {{ $device->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $device->ip_address }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $device->type)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $device->interface_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($device->flow_count) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($device->status === 'online')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Online
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Offline
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Alarms -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Recent Alarms</h3>
                <button onclick="refreshAlarms()" class="text-sm text-indigo-600 hover:text-indigo-900">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
            <div class="p-6" id="alarmsContainer">
                @if($recentAlarms->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No active alarms</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($recentAlarms as $alarm)
                        <div class="border-l-4 {{ $alarm->severity === 'critical' ? 'border-red-500' : ($alarm->severity === 'warning' ? 'border-yellow-500' : 'border-blue-500') }} bg-gray-50 p-4">
                            <div class="flex items-start">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $alarm->title }}</p>
                                    <p class="mt-1 text-sm text-gray-500">{{ $alarm->description }}</p>
                                    <p class="mt-1 text-xs text-gray-400">{{ $alarm->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="ml-3 px-2 py-1 text-xs font-semibold rounded {{ $alarm->severity === 'critical' ? 'bg-red-100 text-red-800' : ($alarm->severity === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($alarm->severity) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
console.log('Dashboard script loaded');

let applicationsChart = null;
let protocolsChart = null;
let isInitializing = false;

// Initialize charts when page loads
window.addEventListener('load', function() {
    console.log('Window loaded, checking for Chart.js...');
    
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded!');
        return;
    }
    
    console.log('Chart.js is loaded, fetching data...');
    
    // Only initialize once
    if (!isInitializing) {
        isInitializing = true;
        initializeCharts();
    }
});

async function initializeCharts() {
    try {
        const response = await fetch('/api/flows/statistics?range=1hour');
        const result = await response.json();
        
        console.log('Statistics received:', result);
        
        if (result.success && result.data) {
            createApplicationChart(result.data.applications || []);
            createProtocolChart(result.data.protocols || []);
        }
    } catch (error) {
        console.error('Error fetching statistics:', error);
    }
}

// Create QoS Chart
function createQoSChart() {
    const ctx = document.getElementById('qosChart');
    if (!ctx) return;
    
    const qosData = @json($topQoS);
    
    if (qosData.length === 0) {
        return;
    }
    
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: qosData.map(item => item.dscp),
            datasets: [{
                data: qosData.map(item => item.bytes),
                backgroundColor: [
                    '#10B981', '#F59E0B', '#EF4444', '#3B82F6', '#8B5CF6',
                    '#EC4899', '#14B8A6', '#F97316', '#06B6D4', '#84CC16'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

// Add to initialization
window.addEventListener('load', function() {
    console.log('Window loaded, checking for Chart.js...');
    
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded!');
        return;
    }
    
    console.log('Chart.js is loaded, fetching data...');
    
    if (!isInitializing) {
        isInitializing = true;
        initializeCharts();
        createQoSChart(); // Add this line
    }
});

function createApplicationChart(applications) {
    const ctx = document.getElementById('applicationsChart');
    if (!ctx) {
        console.error('Applications chart canvas not found');
        return;
    }
    
    // Destroy existing chart
    if (applicationsChart) {
        applicationsChart.destroy();
        applicationsChart = null;
    }
    
    if (applications.length === 0) {
        const context = ctx.getContext('2d');
        context.clearRect(0, 0, ctx.width, ctx.height);
        context.font = '14px Arial';
        context.fillStyle = '#6B7280';
        context.textAlign = 'center';
        context.fillText('No application data available', ctx.width / 2, ctx.height / 2);
        return;
    }
    
    applicationsChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: applications.map(app => app.application || 'Unknown'),
            datasets: [{
                data: applications.map(app => app.bytes),
                backgroundColor: [
                    '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                    '#EC4899', '#14B8A6', '#F97316', '#06B6D4', '#84CC16'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    console.log('Application chart created');
}

function createProtocolChart(protocols) {
    const ctx = document.getElementById('protocolsChart');
    if (!ctx) {
        console.error('Protocols chart canvas not found');
        return;
    }
    
    // Destroy existing chart
    if (protocolsChart) {
        protocolsChart.destroy();
        protocolsChart = null;
    }
    
    if (protocols.length === 0) {
        const context = ctx.getContext('2d');
        context.clearRect(0, 0, ctx.width, ctx.height);
        context.font = '14px Arial';
        context.fillStyle = '#6B7280';
        context.textAlign = 'center';
        context.fillText('No protocol data available', ctx.width / 2, ctx.height / 2);
        return;
    }
    
    protocolsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: protocols.map(p => p.protocol),
            datasets: [{
                label: 'Bytes',
                data: protocols.map(p => p.bytes),
                backgroundColor: '#3B82F6'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000000) {
                                return (value / 1000000000).toFixed(1) + 'GB';
                            } else if (value >= 1000000) {
                                return (value / 1000000).toFixed(1) + 'MB';
                            } else if (value >= 1000) {
                                return (value / 1000).toFixed(1) + 'KB';
                            }
                            return value + 'B';
                        }
                    }
                }
            }
        }
    });
    
    console.log('Protocol chart created');
}

async function updateApplicationChart() {
    const range = document.getElementById('appTimeRange').value;
    console.log('Updating application chart with range:', range);
    
    try {
        const response = await fetch(`/api/flows/statistics?range=${range}`);
        const result = await response.json();
        
        if (result.success && result.data) {
            createApplicationChart(result.data.applications || []);
        }
    } catch (error) {
        console.error('Error updating application chart:', error);
    }
}

async function updateProtocolChart() {
    const range = document.getElementById('protocolTimeRange').value;
    console.log('Updating protocol chart with range:', range);
    
    try {
        const response = await fetch(`/api/flows/statistics?range=${range}`);
        const result = await response.json();
        
        if (result.success && result.data) {
            createProtocolChart(result.data.protocols || []);
        }
    } catch (error) {
        console.error('Error updating protocol chart:', error);
    }
}

async function refreshDeviceTable() {
    try {
        const response = await fetch('/api/devices');
        const result = await response.json();
        
        if (result.success) {
            const tbody = document.getElementById('deviceTableBody');
            tbody.innerHTML = result.data.map(device => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="/devices/${device.id}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                            ${device.name}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${device.ip_address}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${device.type.replace(/_/g, ' ')}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${device.interface_count}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${device.flow_count.toLocaleString()}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${device.status === 'online' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${device.status.charAt(0).toUpperCase() + device.status.slice(1)}
                        </span>
                    </td>
                </tr>
            `).join('');
        }
    } catch (error) {
        console.error('Error refreshing device table:', error);
    }
}

async function refreshAlarms() {
    try {
        const response = await fetch('/api/alarms?status=active');
        const result = await response.json();
        
        if (result.success) {
            const container = document.getElementById('alarmsContainer');
            
            if (result.data.data.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No active alarms</p>
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <div class="space-y-4">
                        ${result.data.data.slice(0, 5).map(alarm => `
                            <div class="border-l-4 ${alarm.severity === 'critical' ? 'border-red-500' : (alarm.severity === 'warning' ? 'border-yellow-500' : 'border-blue-500')} bg-gray-50 p-4">
                                <div class="flex items-start">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">${alarm.title}</p>
                                        <p class="mt-1 text-sm text-gray-500">${alarm.description}</p>
                                    </div>
                                    <span class="ml-3 px-2 py-1 text-xs font-semibold rounded ${alarm.severity === 'critical' ? 'bg-red-100 text-red-800' : (alarm.severity === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')}">
                                        ${alarm.severity.charAt(0).toUpperCase() + alarm.severity.slice(1)}
                                    </span>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
            }
        }
    } catch (error) {
        console.error('Error refreshing alarms:', error);
    }
}
</script>
@endpush