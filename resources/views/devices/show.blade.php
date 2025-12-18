@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('devices.index') }}" class="text-[#5548F5] hover:text-[#9619B5] text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Devices
        </a>
    </div>

    <!-- Device Header -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $device->name }}</h2>
                <p class="text-sm text-gray-500">{{ $device->ip_address }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <form method="GET" id="timeRangeForm" class="flex items-center gap-2">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <span class="text-sm text-gray-600">Time Range:</span>
                    <select name="range" onchange="this.form.submit()"
                        class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#5548F5] focus:border-[#5548F5] cursor-pointer">
                        <option value="1hour" {{ $timeRange === '1hour' ? 'selected' : '' }}>Last Hour</option>
                        <option value="6hours" {{ $timeRange === '6hours' ? 'selected' : '' }}>Last 6 Hours</option>
                        <option value="24hours" {{ $timeRange === '24hours' ? 'selected' : '' }}>Last 24 Hours</option>
                        <option value="7days" {{ $timeRange === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                    </select>
                </form>
                <a href="{{ route('devices.edit', $device) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <span class="px-3 py-1 text-sm font-semibold rounded {{ $device->status === 'online' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($device->status) }}
                </span>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-4 gap-4 pt-4 border-t border-gray-200">
            <div>
                <p class="text-sm text-gray-500">Type</p>
                <p class="text-lg font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $device->type)) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Location</p>
                <p class="text-lg font-medium text-gray-900">{{ $device->location ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Interfaces</p>
                <p class="text-lg font-medium text-gray-900">{{ $device->interface_count }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Flows</p>
                <p class="text-lg font-medium text-gray-900">{{ number_format($device->flow_count) }}</p>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-t-lg shadow">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px overflow-x-auto">
                <a href="?tab=summary&range={{ $timeRange }}"
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'summary' ? 'border-b-2 border-[#5548F5] text-[#5548F5]' : 'text-gray-500 hover:text-[#5548F5] hover:border-[#5548F5]/30' }}">
                    Summary
                </a>
                <a href="?tab=flow-details&range={{ $timeRange }}"
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'flow-details' ? 'border-b-2 border-[#5548F5] text-[#5548F5]' : 'text-gray-500 hover:text-[#5548F5] hover:border-[#5548F5]/30' }}">
                    Flow Details
                </a>
                <a href="?tab=traffic&range={{ $timeRange }}"
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'traffic' ? 'border-b-2 border-[#5548F5] text-[#5548F5]' : 'text-gray-500 hover:text-[#5548F5] hover:border-[#5548F5]/30' }}">
                    Traffic
                </a>
                <a href="?tab=interface&range={{ $timeRange }}"
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'interface' ? 'border-b-2 border-[#5548F5] text-[#5548F5]' : 'text-gray-500 hover:text-[#5548F5] hover:border-[#5548F5]/30' }}">
                    Interface
                </a>
                <a href="?tab=application&range={{ $timeRange }}"
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'application' ? 'border-b-2 border-[#5548F5] text-[#5548F5]' : 'text-gray-500 hover:text-[#5548F5] hover:border-[#5548F5]/30' }}">
                    Application
                </a>
                <a href="?tab=source&range={{ $timeRange }}"
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'source' ? 'border-b-2 border-[#5548F5] text-[#5548F5]' : 'text-gray-500 hover:text-[#5548F5] hover:border-[#5548F5]/30' }}">
                    Source
                </a>
                <a href="?tab=destination&range={{ $timeRange }}"
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'destination' ? 'border-b-2 border-[#5548F5] text-[#5548F5]' : 'text-gray-500 hover:text-[#5548F5] hover:border-[#5548F5]/30' }}">
                    Destination
                </a>
                <a href="?tab=qos&range={{ $timeRange }}"
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'qos' ? 'border-b-2 border-[#5548F5] text-[#5548F5]' : 'text-gray-500 hover:text-[#5548F5] hover:border-[#5548F5]/30' }}">
                    QoS
                </a>
                <a href="?tab=conversation&range={{ $timeRange }}"
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'conversation' ? 'border-b-2 border-[#5548F5] text-[#5548F5]' : 'text-gray-500 hover:text-[#5548F5] hover:border-[#5548F5]/30' }}">
                    Conversation
                </a>
                <a href="?tab=as-view&range={{ $timeRange }}"
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'as-view' ? 'border-b-2 border-[#5548F5] text-[#5548F5]' : 'text-gray-500 hover:text-[#5548F5] hover:border-[#5548F5]/30' }}">
                    AS View
                </a>
                <a href="?tab=cloud-services&range={{ $timeRange }}"
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'cloud-services' ? 'border-b-2 border-[#5548F5] text-[#5548F5]' : 'text-gray-500 hover:text-[#5548F5] hover:border-[#5548F5]/30' }}">
                    Cloud Services
                </a>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            @if($tab === 'summary')
                @include('devices.tabs.summary')
            @elseif($tab === 'flow-details')
                @include('devices.tabs.flow-details')
            @elseif($tab === 'traffic')
                @include('devices.tabs.traffic')
            @elseif($tab === 'interface')
                @include('devices.tabs.interface')
            @elseif($tab === 'application')
                @include('devices.tabs.application')
            @elseif($tab === 'source')
                @include('devices.tabs.source')
            @elseif($tab === 'destination')
                @include('devices.tabs.destination')
            @elseif($tab === 'qos')
                @include('devices.tabs.qos')
            @elseif($tab === 'conversation')
                @include('devices.tabs.conversation')
            @elseif($tab === 'as-view')
                @include('devices.tabs.as-view')
            @elseif($tab === 'cloud-services')
                @include('devices.tabs.cloud-services')
            @endif
        </div>
    </div>
</div>
@endsection