@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('devices.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
            ← Back to Devices
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
                <form method="GET" class="flex gap-2">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <select name="range" class="border-gray-300 rounded-md text-sm" onchange="this.form.submit()">
                        <option value="1hour" {{ $timeRange === '1hour' ? 'selected' : '' }}>Last Hour</option>
                        <option value="6hours" {{ $timeRange === '6hours' ? 'selected' : '' }}>Last 6 Hours</option>
                        <option value="24hours" {{ $timeRange === '24hours' ? 'selected' : '' }}>Last 24 Hours</option>
                        <option value="7days" {{ $timeRange === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                    </select>
                </form>
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
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'summary' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Summary
                </a>
                <a href="?tab=flow-details&range={{ $timeRange }}" 
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'flow-details' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Flow Details
                </a>
                <a href="?tab=traffic&range={{ $timeRange }}" 
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'traffic' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Traffic
                </a>
                <a href="?tab=interface&range={{ $timeRange }}" 
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'interface' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Interface
                </a>
                <a href="?tab=application&range={{ $timeRange }}" 
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'application' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Application
                </a>
                <a href="?tab=source&range={{ $timeRange }}" 
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'source' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Source
                </a>
                <a href="?tab=destination&range={{ $timeRange }}" 
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'destination' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Destination
                </a>
                <a href="?tab=qos&range={{ $timeRange }}" 
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'qos' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    QoS
                </a>
                <a href="?tab=conversation&range={{ $timeRange }}" 
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'conversation' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Conversation
                </a>
                <a href="?tab=as-view&range={{ $timeRange }}" 
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'as-view' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    AS View
                </a>
                <a href="?tab=cloud-services&range={{ $timeRange }}" 
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'cloud-services' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Cloud Services
                </a>
                <a href="?tab=users&range={{ $timeRange }}" 
                   class="px-6 py-3 text-sm font-medium {{ $tab === 'users' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Users
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
            @elseif($tab === 'users')
                @include('devices.tabs.users')
            @endif
        </div>
    </div>
</div>
@endsection