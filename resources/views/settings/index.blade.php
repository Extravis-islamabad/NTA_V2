@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 gradient-primary rounded-2xl flex items-center justify-center shadow-lg pulse-glow">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('MonetX_black@4x-8.png') }}" alt="MonetX" class="h-8 w-auto">
                    <span class="text-gray-400 text-2xl font-light">|</span>
                    <h2 class="text-2xl font-bold text-gray-900">Settings</h2>
                </div>
                <p class="text-gray-600 mt-1">Configure your NetFlow Traffic Analyzer settings</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Settings Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- NetFlow Collector Configuration -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow card-hover">
                    <div class="bg-gradient-to-r from-[#5548F5] to-[#C843F3] px-6 py-5">
                        <h3 class="text-lg font-bold text-white flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            NetFlow Collector Configuration
                        </h3>
                        <p class="text-white/80 text-sm mt-2 ml-13">Configure the primary NetFlow data collector settings</p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Collector IP -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                        Collector IP Address
                                    </span>
                                </label>
                                <input type="text" name="collector_ip" value="{{ old('collector_ip', $settings['collector_ip'] ?? '192.168.10.7') }}"
                                       class="w-full border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] font-mono text-sm py-3 shadow-sm"
                                       placeholder="192.168.10.7">
                                <p class="text-xs text-gray-500">IP address where NetFlow data will be received</p>
                                @error('collector_ip')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- NetFlow Port -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        NetFlow Listener Port
                                    </span>
                                </label>
                                <input type="number" name="netflow_port" value="{{ old('netflow_port', $settings['netflow_port'] ?? 2055) }}"
                                       class="w-full border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] font-mono text-sm py-3 shadow-sm"
                                       required min="1024" max="65535">
                                <p class="text-xs text-gray-500">UDP port for receiving NetFlow packets (default: 2055)</p>
                                @error('netflow_port')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- sFlow Port -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#C843F3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        sFlow Listener Port
                                    </span>
                                </label>
                                <input type="number" name="sflow_port" value="{{ old('sflow_port', $settings['sflow_port'] ?? 6343) }}"
                                       class="w-full border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] font-mono text-sm py-3 shadow-sm"
                                       min="1024" max="65535">
                                <p class="text-xs text-gray-500">UDP port for receiving sFlow packets (default: 6343)</p>
                            </div>

                            <!-- IPFIX Port -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#9619B5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        IPFIX Listener Port
                                    </span>
                                </label>
                                <input type="number" name="ipfix_port" value="{{ old('ipfix_port', $settings['ipfix_port'] ?? 4739) }}"
                                       class="w-full border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] font-mono text-sm py-3 shadow-sm"
                                       min="1024" max="65535">
                                <p class="text-xs text-gray-500">UDP port for receiving IPFIX packets (default: 4739)</p>
                            </div>
                        </div>

                        <!-- NetFlow Version Support -->
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#C843F3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    Supported NetFlow Versions
                                </span>
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <label class="flex items-center p-4 bg-[#E4F2FF] rounded-xl cursor-pointer hover:bg-[#E4F2FF]/80 transition border-2 border-transparent has-[:checked]:border-[#5548F5]">
                                    <input type="checkbox" name="netflow_v5" value="1"
                                           {{ old('netflow_v5', $settings['netflow_v5'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-[#5548F5] focus:ring-[#5548F5]">
                                    <span class="ml-3">
                                        <span class="block text-sm font-semibold text-gray-900">NetFlow v5</span>
                                        <span class="block text-xs text-gray-500">Legacy format</span>
                                    </span>
                                </label>
                                <label class="flex items-center p-4 bg-[#E4F2FF] rounded-xl cursor-pointer hover:bg-[#E4F2FF]/80 transition border-2 border-transparent has-[:checked]:border-[#5548F5]">
                                    <input type="checkbox" name="netflow_v9" value="1"
                                           {{ old('netflow_v9', $settings['netflow_v9'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-[#5548F5] focus:ring-[#5548F5]">
                                    <span class="ml-3">
                                        <span class="block text-sm font-semibold text-gray-900">NetFlow v9</span>
                                        <span class="block text-xs text-gray-500">Template based</span>
                                    </span>
                                </label>
                                <label class="flex items-center p-4 bg-[#F2C7FF] rounded-xl cursor-pointer hover:bg-[#F2C7FF]/80 transition border-2 border-transparent has-[:checked]:border-[#C843F3]">
                                    <input type="checkbox" name="ipfix" value="1"
                                           {{ old('ipfix', $settings['ipfix'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-[#C843F3] focus:ring-[#C843F3]">
                                    <span class="ml-3">
                                        <span class="block text-sm font-semibold text-gray-900">IPFIX</span>
                                        <span class="block text-xs text-gray-500">v10 Standard</span>
                                    </span>
                                </label>
                                <label class="flex items-center p-4 bg-[#F2C7FF] rounded-xl cursor-pointer hover:bg-[#F2C7FF]/80 transition border-2 border-transparent has-[:checked]:border-[#9619B5]">
                                    <input type="checkbox" name="sflow" value="1"
                                           {{ old('sflow', $settings['sflow'] ?? false) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-[#9619B5] focus:ring-[#9619B5]">
                                    <span class="ml-3">
                                        <span class="block text-sm font-semibold text-gray-900">sFlow</span>
                                        <span class="block text-xs text-gray-500">Sampling</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Sample Rate -->
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#C843F3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            Default Sample Rate
                                        </span>
                                    </label>
                                    <select name="sample_rate" class="w-full border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] py-3 shadow-sm">
                                        <option value="1" {{ ($settings['sample_rate'] ?? 1) == 1 ? 'selected' : '' }}>1:1 (No Sampling)</option>
                                        <option value="100" {{ ($settings['sample_rate'] ?? 1) == 100 ? 'selected' : '' }}>1:100</option>
                                        <option value="500" {{ ($settings['sample_rate'] ?? 1) == 500 ? 'selected' : '' }}>1:500</option>
                                        <option value="1000" {{ ($settings['sample_rate'] ?? 1) == 1000 ? 'selected' : '' }}>1:1000</option>
                                        <option value="2000" {{ ($settings['sample_rate'] ?? 1) == 2000 ? 'selected' : '' }}>1:2000</option>
                                        <option value="4000" {{ ($settings['sample_rate'] ?? 1) == 4000 ? 'selected' : '' }}>1:4000</option>
                                    </select>
                                    <p class="text-xs text-gray-500">Flow sampling rate applied to received data</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Active Timeout (seconds)
                                        </span>
                                    </label>
                                    <input type="number" name="active_timeout" value="{{ old('active_timeout', $settings['active_timeout'] ?? 60) }}"
                                           class="w-full border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] font-mono text-sm py-3 shadow-sm"
                                           min="30" max="600">
                                    <p class="text-xs text-gray-500">How often to export active flows (30-600 sec)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Management Section -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow card-hover">
                    <div class="bg-gradient-to-r from-[#C843F3] to-[#9619B5] px-6 py-5">
                        <h3 class="text-lg font-bold text-white flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                                </svg>
                            </div>
                            Data Management
                        </h3>
                        <p class="text-white/80 text-sm mt-2 ml-13">Configure data retention and aggregation policies</p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Retention Days -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Data Retention Period</label>
                                <div class="flex items-center gap-3">
                                    <input type="number" name="retention_days" value="{{ old('retention_days', $settings['retention_days'] ?? 7) }}"
                                           class="w-24 border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] py-3 shadow-sm"
                                           required min="1" max="365">
                                    <span class="text-gray-600 font-medium">days</span>
                                </div>
                                <p class="text-xs text-gray-500">Flow data older than this will be automatically purged</p>
                                @error('retention_days')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Aggregation Interval -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Aggregation Interval</label>
                                <select name="aggregation_interval" class="w-full border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] py-3 shadow-sm">
                                    <option value="1min" {{ ($settings['aggregation_interval'] ?? '1min') == '1min' ? 'selected' : '' }}>1 Minute</option>
                                    <option value="5min" {{ ($settings['aggregation_interval'] ?? '1min') == '5min' ? 'selected' : '' }}>5 Minutes</option>
                                    <option value="10min" {{ ($settings['aggregation_interval'] ?? '1min') == '10min' ? 'selected' : '' }}>10 Minutes</option>
                                    <option value="15min" {{ ($settings['aggregation_interval'] ?? '1min') == '15min' ? 'selected' : '' }}>15 Minutes</option>
                                    <option value="1hour" {{ ($settings['aggregation_interval'] ?? '1min') == '1hour' ? 'selected' : '' }}>1 Hour</option>
                                </select>
                                <p class="text-xs text-gray-500">Time interval for traffic data aggregation</p>
                            </div>
                        </div>

                        <!-- Toggle Options -->
                        <div class="mt-6 pt-6 border-t border-gray-100 space-y-4">
                            <label class="flex items-center p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                                <input type="checkbox" name="aggregation_enabled" value="1"
                                       {{ old('aggregation_enabled', $settings['aggregation_enabled'] ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-[#5548F5] focus:ring-[#5548F5] w-5 h-5">
                                <span class="ml-4">
                                    <span class="block text-sm font-semibold text-gray-900">Enable automatic traffic aggregation</span>
                                    <span class="block text-xs text-gray-500 mt-1">Automatically aggregate traffic data for improved reporting performance</span>
                                </span>
                            </label>

                            <label class="flex items-center p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                                <input type="checkbox" name="dns_resolution" value="1"
                                       {{ old('dns_resolution', $settings['dns_resolution'] ?? false) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-[#5548F5] focus:ring-[#5548F5] w-5 h-5">
                                <span class="ml-4">
                                    <span class="block text-sm font-semibold text-gray-900">Enable DNS resolution</span>
                                    <span class="block text-xs text-gray-500 mt-1">Resolve IP addresses to hostnames (may impact performance)</span>
                                </span>
                            </label>

                            <label class="flex items-center p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                                <input type="checkbox" name="geolocation_enabled" value="1"
                                       {{ old('geolocation_enabled', $settings['geolocation_enabled'] ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-[#5548F5] focus:ring-[#5548F5] w-5 h-5">
                                <span class="ml-4">
                                    <span class="block text-sm font-semibold text-gray-900">Enable IP geolocation</span>
                                    <span class="block text-xs text-gray-500 mt-1">Identify geographic location of IP addresses</span>
                                </span>
                            </label>

                            <label class="flex items-center p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                                <input type="checkbox" name="as_lookup_enabled" value="1"
                                       {{ old('as_lookup_enabled', $settings['as_lookup_enabled'] ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-[#5548F5] focus:ring-[#5548F5] w-5 h-5">
                                <span class="ml-4">
                                    <span class="block text-sm font-semibold text-gray-900">Enable AS (Autonomous System) lookup</span>
                                    <span class="block text-xs text-gray-500 mt-1">Identify network ownership and BGP information</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Alert Settings Section -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow card-hover">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-red-50">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            Alert Thresholds
                        </h3>
                        <p class="text-gray-600 text-sm mt-2 ml-13">Configure when to trigger alerts</p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">High Traffic Threshold</label>
                                <div class="flex items-center gap-3">
                                    <input type="number" name="traffic_threshold" value="{{ old('traffic_threshold', $settings['traffic_threshold'] ?? 1000) }}"
                                           class="flex-1 border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] py-3 shadow-sm"
                                           min="1">
                                    <span class="text-gray-600 font-medium">Mbps</span>
                                </div>
                                <p class="text-xs text-gray-500">Alert when traffic exceeds this threshold</p>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Device Offline Timeout</label>
                                <div class="flex items-center gap-3">
                                    <input type="number" name="offline_timeout" value="{{ old('offline_timeout', $settings['offline_timeout'] ?? 5) }}"
                                           class="flex-1 border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] py-3 shadow-sm"
                                           min="1" max="60">
                                    <span class="text-gray-600 font-medium">minutes</span>
                                </div>
                                <p class="text-xs text-gray-500">Mark device offline after no data received</p>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Interface Utilization Warning</label>
                                <div class="flex items-center gap-3">
                                    <input type="number" name="utilization_warning" value="{{ old('utilization_warning', $settings['utilization_warning'] ?? 80) }}"
                                           class="flex-1 border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] py-3 shadow-sm"
                                           min="1" max="100">
                                    <span class="text-gray-600 font-medium">%</span>
                                </div>
                                <p class="text-xs text-gray-500">Warn when interface utilization exceeds this</p>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Interface Utilization Critical</label>
                                <div class="flex items-center gap-3">
                                    <input type="number" name="utilization_critical" value="{{ old('utilization_critical', $settings['utilization_critical'] ?? 95) }}"
                                           class="flex-1 border-gray-300 rounded-xl focus:ring-[#5548F5] focus:border-[#5548F5] py-3 shadow-sm"
                                           min="1" max="100">
                                    <span class="text-gray-600 font-medium">%</span>
                                </div>
                                <p class="text-xs text-gray-500">Critical alert when utilization exceeds this</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-8 py-3 btn-monetx text-white rounded-xl font-semibold flex items-center gap-2 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Settings
                    </button>
                </div>
            </div>

            <!-- Right Column - Info Cards -->
            <div class="space-y-6">
                <!-- Collector Quick Info -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow card-hover">
                    <div class="gradient-light px-6 py-5">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Collector Configuration
                        </h3>
                    </div>

                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">Configure your network devices to send flow data to:</p>
                        <div class="space-y-3">
                            <div class="bg-[#E4F2FF] rounded-xl p-4">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Collector IP</p>
                                <p class="text-2xl font-mono font-bold text-[#5548F5] mt-1">{{ $settings['collector_ip'] ?? '192.168.10.7' }}</p>
                            </div>
                            <div class="bg-[#F2C7FF] rounded-xl p-4">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">NetFlow Port (UDP)</p>
                                <p class="text-2xl font-mono font-bold text-[#9619B5] mt-1">{{ $settings['netflow_port'] ?? 2055 }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">sFlow Port</p>
                                    <p class="text-lg font-mono font-bold text-gray-700">{{ $settings['sflow_port'] ?? 6343 }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">IPFIX Port</p>
                                    <p class="text-lg font-mono font-bold text-gray-700">{{ $settings['ipfix_port'] ?? 4739 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configuration Examples -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow card-hover">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#9619B5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                            Device Configuration Examples
                        </h3>
                    </div>

                    <div class="p-6" x-data="{ activeTab: 'cisco' }">
                        <div class="flex flex-wrap gap-2 mb-4">
                            <button type="button" @click="activeTab = 'cisco'" :class="activeTab === 'cisco' ? 'bg-[#5548F5] text-white shadow-lg' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                    class="px-4 py-2 text-xs font-semibold rounded-lg transition">Cisco IOS</button>
                            <button type="button" @click="activeTab = 'fortigate'" :class="activeTab === 'fortigate' ? 'bg-[#5548F5] text-white shadow-lg' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                    class="px-4 py-2 text-xs font-semibold rounded-lg transition">FortiGate</button>
                            <button type="button" @click="activeTab = 'paloalto'" :class="activeTab === 'paloalto' ? 'bg-[#5548F5] text-white shadow-lg' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                    class="px-4 py-2 text-xs font-semibold rounded-lg transition">Palo Alto</button>
                            <button type="button" @click="activeTab = 'juniper'" :class="activeTab === 'juniper' ? 'bg-[#5548F5] text-white shadow-lg' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                    class="px-4 py-2 text-xs font-semibold rounded-lg transition">Juniper</button>
                        </div>

                        <div x-show="activeTab === 'cisco'">
                            <pre class="bg-gray-900 text-green-400 p-4 rounded-xl text-xs overflow-x-auto max-h-48 scrollbar-thin">flow exporter MONETX-EXPORT
 destination {{ $settings['collector_ip'] ?? '192.168.10.7' }}
 transport udp {{ $settings['netflow_port'] ?? 2055 }}
 export-protocol netflow-v9

flow monitor MONETX-MONITOR
 exporter MONETX-EXPORT
 record netflow ipv4 original-input

interface GigabitEthernet0/0
 ip flow monitor MONETX-MONITOR input
 ip flow monitor MONETX-MONITOR output</pre>
                        </div>

                        <div x-show="activeTab === 'fortigate'" style="display: none;">
                            <pre class="bg-gray-900 text-green-400 p-4 rounded-xl text-xs overflow-x-auto max-h-48">config system netflow
    set collector-ip {{ $settings['collector_ip'] ?? '192.168.10.7' }}
    set collector-port {{ $settings['netflow_port'] ?? 2055 }}
    set source-ip auto
end

config system interface
    edit "port1"
        set netflow-sampler both
    next
end</pre>
                        </div>

                        <div x-show="activeTab === 'paloalto'" style="display: none;">
                            <pre class="bg-gray-900 text-green-400 p-4 rounded-xl text-xs overflow-x-auto max-h-48">set deviceconfig system netflow
    exporter-1 server {{ $settings['collector_ip'] ?? '192.168.10.7' }}
set deviceconfig system netflow
    exporter-1 port {{ $settings['netflow_port'] ?? 2055 }}
set deviceconfig system netflow
    exporter-1 template-refresh-rate 20
commit</pre>
                        </div>

                        <div x-show="activeTab === 'juniper'" style="display: none;">
                            <pre class="bg-gray-900 text-green-400 p-4 rounded-xl text-xs overflow-x-auto max-h-48">set services flow-monitoring version-ipfix
    template ipv4
set forwarding-options sampling instance sample
    input rate 1
set forwarding-options sampling instance sample
    family inet output
    flow-server {{ $settings['collector_ip'] ?? '192.168.10.7' }} port {{ $settings['netflow_port'] ?? 2055 }}
    version-ipfix template ipv4</pre>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden monetx-shadow card-hover">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#5548F5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                            </svg>
                            System Status
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">NetFlow Collector</p>
                                    <p class="text-xs text-gray-500">Port :{{ $settings['netflow_port'] ?? 2055 }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1.5 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Running</span>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Queue Worker</p>
                                    <p class="text-xs text-gray-500">Processing flows</p>
                                </div>
                            </div>
                            <span class="px-3 py-1.5 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Active</span>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Scheduler</p>
                                    <p class="text-xs text-gray-500">Aggregation tasks</p>
                                </div>
                            </div>
                            <span class="px-3 py-1.5 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Running</span>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-2 gap-3 text-center">
                                <div class="bg-[#E4F2FF] rounded-xl p-4">
                                    <p class="text-xs text-gray-500 font-semibold">Laravel</p>
                                    <p class="text-lg font-bold text-[#5548F5]">{{ app()->version() }}</p>
                                </div>
                                <div class="bg-[#F2C7FF] rounded-xl p-4">
                                    <p class="text-xs text-gray-500 font-semibold">PHP</p>
                                    <p class="text-lg font-bold text-[#9619B5]">{{ PHP_VERSION }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
