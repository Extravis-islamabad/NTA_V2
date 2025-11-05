@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Reports</h2>
        <p class="text-gray-600">Generate and export network traffic reports</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Traffic Report -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <div class="bg-indigo-100 rounded-full p-3 mr-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Traffic Report</h3>
                    <p class="text-sm text-gray-500">Analyze traffic patterns and usage</p>
                </div>
            </div>
            <p class="text-gray-600 mb-4">
                Generate detailed reports on network traffic including top applications, protocols, and bandwidth usage over a custom time period.
            </p>
            <a href="{{ route('reports.traffic') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                Generate Report
            </a>
        </div>

        <!-- Device Report -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 rounded-full p-3 mr-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Device Report</h3>
                    <p class="text-sm text-gray-500">Inventory and device statistics</p>
                </div>
            </div>
            <p class="text-gray-600 mb-4">
                Generate comprehensive reports on all network devices including status, interfaces, and flow counts.
            </p>
            <a href="{{ route('reports.devices') }}" class="inline-block bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                Generate Report
            </a>
        </div>
    </div>
</div>
@endsection