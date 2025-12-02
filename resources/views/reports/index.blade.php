@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 gradient-primary rounded-2xl flex items-center justify-center shadow-lg pulse-glow">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('MonetX_black@4x-8.png') }}" alt="MonetX" class="h-8 w-auto">
                    <span class="text-gray-300 text-2xl font-light">|</span>
                    <h2 class="text-2xl font-bold text-gray-900">Reports Center</h2>
                </div>
                <p class="text-gray-600 mt-1">Generate comprehensive network traffic analytics reports</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 monetx-shadow card-hover stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Devices</p>
                    <p class="text-3xl font-bold text-[#5548F5] mt-1">{{ number_format($stats['total_devices']) }}</p>
                </div>
                <div class="w-12 h-12 bg-[#E4F2FF] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-xs text-gray-500">{{ $stats['online_devices'] }} online</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 monetx-shadow card-hover stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Flows</p>
                    <p class="text-3xl font-bold text-[#C843F3] mt-1">{{ number_format($stats['total_flows']) }}</p>
                </div>
                <div class="w-12 h-12 bg-[#F2C7FF] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#C843F3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-500">Captured network flows</div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 monetx-shadow card-hover stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Traffic</p>
                    <p class="text-3xl font-bold text-[#9619B5] mt-1">
                        @php
                            $bytes = $stats['total_bytes'];
                            if ($bytes >= 1099511627776) {
                                echo round($bytes / 1099511627776, 2) . ' TB';
                            } elseif ($bytes >= 1073741824) {
                                echo round($bytes / 1073741824, 2) . ' GB';
                            } elseif ($bytes >= 1048576) {
                                echo round($bytes / 1048576, 2) . ' MB';
                            } else {
                                echo round($bytes / 1024, 2) . ' KB';
                            }
                        @endphp
                    </p>
                </div>
                <div class="w-12 h-12 bg-[#F2C7FF] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#9619B5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-500">Bandwidth analyzed</div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 monetx-shadow card-hover stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Report Types</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">3</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-500">Available report templates</div>
        </div>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Traffic Analysis Report -->
        <a href="{{ route('reports.traffic') }}" class="group">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow card-hover h-full">
                <div class="h-2 bg-gradient-to-r from-[#5548F5] to-[#C843F3]"></div>
                <div class="p-6">
                    <div class="w-14 h-14 gradient-primary rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-[#5548F5] transition">Traffic Analysis</h3>
                    <p class="text-gray-600 text-sm mb-4">Comprehensive bandwidth analysis with application and protocol breakdown over custom time periods.</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 bg-[#E4F2FF] text-[#5548F5] text-xs font-semibold rounded-full">Applications</span>
                        <span class="px-3 py-1 bg-[#F2C7FF] text-[#9619B5] text-xs font-semibold rounded-full">Protocols</span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Bandwidth</span>
                    </div>
                    <div class="flex items-center text-[#5548F5] font-semibold group-hover:gap-3 transition-all">
                        <span>Generate Report</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- Device Inventory Report -->
        <a href="{{ route('reports.devices') }}" class="group">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow card-hover h-full">
                <div class="h-2 bg-gradient-to-r from-[#C843F3] to-[#9619B5]"></div>
                <div class="p-6">
                    <div class="w-14 h-14 bg-gradient-to-r from-[#C843F3] to-[#9619B5] rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-[#C843F3] transition">Device Inventory</h3>
                    <p class="text-gray-600 text-sm mb-4">Complete overview of all monitored network devices with status, interfaces, and flow statistics.</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 bg-[#E4F2FF] text-[#5548F5] text-xs font-semibold rounded-full">Devices</span>
                        <span class="px-3 py-1 bg-[#F2C7FF] text-[#9619B5] text-xs font-semibold rounded-full">Interfaces</span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Status</span>
                    </div>
                    <div class="flex items-center text-[#C843F3] font-semibold group-hover:gap-3 transition-all">
                        <span>Generate Report</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- Top Talkers Report -->
        <a href="{{ route('reports.talkers') }}" class="group">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow card-hover h-full">
                <div class="h-2 bg-gradient-to-r from-[#3B82F6] to-[#5548F5]"></div>
                <div class="p-6">
                    <div class="w-14 h-14 bg-gradient-to-r from-[#3B82F6] to-[#5548F5] rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition">Top Talkers</h3>
                    <p class="text-gray-600 text-sm mb-4">Identify your heaviest bandwidth consumers with detailed source, destination, and conversation analysis.</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Sources</span>
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full">Destinations</span>
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">Conversations</span>
                    </div>
                    <div class="flex items-center text-blue-600 font-semibold group-hover:gap-3 transition-all">
                        <span>Generate Report</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Export Options & Report Guide -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Export -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Quick Export
                </h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-6">Export reports in CSV format for external analysis and archiving.</p>
                <div class="space-y-3">
                    <a href="{{ route('reports.export') }}?type=traffic&start_date={{ now()->subDay()->format('Y-m-d\TH:i') }}&end_date={{ now()->format('Y-m-d\TH:i') }}"
                       class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Traffic Report (Last 24h)</p>
                                <p class="text-xs text-gray-500">Flow-level data export</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>

                    <a href="{{ route('reports.export') }}?type=devices"
                       class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#E4F2FF] rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Device Inventory</p>
                                <p class="text-xs text-gray-500">Complete device list export</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>

                    <a href="{{ route('reports.export') }}?type=talkers&start_date={{ now()->subDay()->format('Y-m-d\TH:i') }}&end_date={{ now()->format('Y-m-d\TH:i') }}"
                       class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Top Talkers (Last 24h)</p>
                                <p class="text-xs text-gray-500">Bandwidth consumers export</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Report Guide -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow">
            <div class="px-6 py-4 border-b border-gray-100 gradient-light">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Report Guide
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="w-8 h-8 gradient-primary rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-sm">1</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Select Report Type</h4>
                            <p class="text-sm text-gray-600">Choose from Traffic Analysis, Device Inventory, or Top Talkers based on your analysis needs.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-8 h-8 gradient-primary rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-sm">2</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Configure Parameters</h4>
                            <p class="text-sm text-gray-600">Set date ranges, select specific devices, and configure filters for targeted analysis.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-8 h-8 gradient-primary rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-sm">3</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Generate & Analyze</h4>
                            <p class="text-sm text-gray-600">View interactive charts and detailed tables with sorting and filtering capabilities.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-8 h-8 gradient-primary rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-sm">4</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Export Data</h4>
                            <p class="text-sm text-gray-600">Download CSV files for external analysis, archiving, or sharing with stakeholders.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-[#E4F2FF] rounded-xl">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#5548F5] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-gray-700">
                            <span class="font-semibold">Pro Tip:</span> For best performance, limit report date ranges to 7 days or less when analyzing large networks.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
