@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-cyan-500/20 border border-cyan-500/30 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('MonetX_white@4x-8.png') }}" alt="MonetX" class="h-8 w-auto">
                    <span class="text-gray-500 text-2xl font-light">|</span>
                    <h2 class="text-2xl font-bold text-white">Settings</h2>
                </div>
                <p class="text-gray-400 mt-1">Configure your NetFlow Traffic Analyzer settings</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="glass-card bg-emerald-500/20 border-emerald-500/30 text-emerald-300 px-6 py-4 rounded-xl mb-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-500/20 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="glass-card overflow-hidden" x-data="{ open: true }">
                    <button type="button" @click="open = !open" class="section-header w-full flex items-center justify-between cursor-pointer hover:bg-[var(--bg-hover)] transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-cyan-500/20 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <h3 class="text-lg font-bold text-white">NetFlow Collector Configuration</h3>
                                <p class="text-sm text-gray-400">Configure the primary NetFlow data collector settings</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" x-collapse>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Collector IP -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-300">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                            </svg>
                                            Collector IP Address
                                        </span>
                                    </label>
                                    <input type="text" name="collector_ip" value="{{ old('collector_ip', $settings['collector_ip'] ?? '') }}"
                                           class="w-full glass-input rounded-xl font-mono text-sm py-3 px-4"
                                           placeholder="Enter collector IP address" required>
                                    <p class="text-xs text-gray-500">IP address where NetFlow data will be received</p>
                                    @error('collector_ip')
                                        <p class="text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- NetFlow Port -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-300">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            NetFlow Listener Port
                                        </span>
                                    </label>
                                    <input type="number" name="netflow_port" value="{{ old('netflow_port', $settings['netflow_port'] ?? '') }}"
                                           class="w-full glass-input rounded-xl font-mono text-sm py-3 px-4"
                                           required min="1024" max="65535" placeholder="e.g., 2055">
                                    <p class="text-xs text-gray-500">UDP port for receiving NetFlow packets</p>
                                    @error('netflow_port')
                                        <p class="text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- sFlow Port -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-300">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            sFlow Listener Port
                                        </span>
                                    </label>
                                    <input type="number" name="sflow_port" value="{{ old('sflow_port', $settings['sflow_port'] ?? '') }}"
                                           class="w-full glass-input rounded-xl font-mono text-sm py-3 px-4"
                                           min="1024" max="65535" placeholder="e.g., 6343">
                                    <p class="text-xs text-gray-500">UDP port for receiving sFlow packets</p>
                                </div>

                                <!-- IPFIX Port -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-300">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            IPFIX Listener Port
                                        </span>
                                    </label>
                                    <input type="number" name="ipfix_port" value="{{ old('ipfix_port', $settings['ipfix_port'] ?? '') }}"
                                           class="w-full glass-input rounded-xl font-mono text-sm py-3 px-4"
                                           min="1024" max="65535" placeholder="e.g., 4739">
                                    <p class="text-xs text-gray-500">UDP port for receiving IPFIX packets</p>
                                </div>
                            </div>

                            <!-- NetFlow Version Support -->
                            <div class="mt-6 pt-6 border-t border-white/10">
                                <label class="block text-sm font-semibold text-gray-300 mb-3">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Supported NetFlow Versions
                                    </span>
                                </label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <label class="flex items-center p-4 bg-cyan-500/10 rounded-xl cursor-pointer hover:bg-cyan-500/20 transition border border-cyan-500/20 has-[:checked]:border-cyan-500">
                                        <input type="checkbox" name="netflow_v5" value="1"
                                               {{ old('netflow_v5', $settings['netflow_v5'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-600 text-cyan-500 focus:ring-cyan-500 bg-white/10">
                                        <span class="ml-3">
                                            <span class="block text-sm font-semibold text-white">NetFlow v5</span>
                                            <span class="block text-xs text-gray-400">Legacy format</span>
                                        </span>
                                    </label>
                                    <label class="flex items-center p-4 bg-cyan-500/10 rounded-xl cursor-pointer hover:bg-cyan-500/20 transition border border-cyan-500/20 has-[:checked]:border-cyan-500">
                                        <input type="checkbox" name="netflow_v9" value="1"
                                               {{ old('netflow_v9', $settings['netflow_v9'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-600 text-cyan-500 focus:ring-cyan-500 bg-white/10">
                                        <span class="ml-3">
                                            <span class="block text-sm font-semibold text-white">NetFlow v9</span>
                                            <span class="block text-xs text-gray-400">Template based</span>
                                        </span>
                                    </label>
                                    <label class="flex items-center p-4 bg-indigo-500/10 rounded-xl cursor-pointer hover:bg-indigo-500/20 transition border border-indigo-500/20 has-[:checked]:border-indigo-500">
                                        <input type="checkbox" name="ipfix" value="1"
                                               {{ old('ipfix', $settings['ipfix'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-600 text-indigo-500 focus:ring-indigo-500 bg-white/10">
                                        <span class="ml-3">
                                            <span class="block text-sm font-semibold text-white">IPFIX</span>
                                            <span class="block text-xs text-gray-400">v10 Standard</span>
                                        </span>
                                    </label>
                                    <label class="flex items-center p-4 bg-indigo-500/10 rounded-xl cursor-pointer hover:bg-indigo-500/20 transition border border-indigo-500/20 has-[:checked]:border-indigo-500">
                                        <input type="checkbox" name="sflow" value="1"
                                               {{ old('sflow', $settings['sflow'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-600 text-indigo-500 focus:ring-indigo-500 bg-white/10">
                                        <span class="ml-3">
                                            <span class="block text-sm font-semibold text-white">sFlow</span>
                                            <span class="block text-xs text-gray-400">Sampling</span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <!-- Sample Rate -->
                            <div class="mt-6 pt-6 border-t border-white/10">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-300">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                </svg>
                                                Default Sample Rate
                                            </span>
                                        </label>
                                        <select name="sample_rate" class="w-full glass-input rounded-xl py-3 px-4">
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
                                        <label class="block text-sm font-semibold text-gray-300">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Active Timeout (seconds)
                                            </span>
                                        </label>
                                        <input type="number" name="active_timeout" value="{{ old('active_timeout', $settings['active_timeout'] ?? 60) }}"
                                               class="w-full glass-input rounded-xl font-mono text-sm py-3 px-4"
                                               min="30" max="600">
                                        <p class="text-xs text-gray-500">How often to export active flows (30-600 sec)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Management Section -->
                <div class="glass-card overflow-hidden" x-data="{ open: true }">
                    <button type="button" @click="open = !open" class="section-header w-full flex items-center justify-between cursor-pointer hover:bg-[var(--bg-hover)] transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-500/20 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <h3 class="text-lg font-bold text-white">Data Management</h3>
                                <p class="text-sm text-gray-400">Configure data retention and aggregation policies</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" x-collapse>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Retention Days -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-300">Data Retention Period</label>
                                    <div class="flex items-center gap-3">
                                        <input type="number" name="retention_days" value="{{ old('retention_days', $settings['retention_days'] ?? 7) }}"
                                               class="w-24 glass-input rounded-xl py-3 px-4"
                                               required min="1" max="365">
                                        <span class="text-gray-400 font-medium">days</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Flow data older than this will be automatically purged</p>
                                    @error('retention_days')
                                        <p class="text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Aggregation Interval -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-300">Aggregation Interval</label>
                                    <select name="aggregation_interval" class="w-full glass-input rounded-xl py-3 px-4">
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
                            <div class="mt-6 pt-6 border-t border-white/10 space-y-4">
                                <label class="flex items-center p-4 bg-white/5 rounded-xl cursor-pointer hover:bg-white/10 transition">
                                    <input type="checkbox" name="aggregation_enabled" value="1"
                                           {{ old('aggregation_enabled', $settings['aggregation_enabled'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-600 text-cyan-500 focus:ring-cyan-500 w-5 h-5 bg-white/10">
                                    <span class="ml-4">
                                        <span class="block text-sm font-semibold text-white">Enable automatic traffic aggregation</span>
                                        <span class="block text-xs text-gray-400 mt-1">Automatically aggregate traffic data for improved reporting performance</span>
                                    </span>
                                </label>

                                <label class="flex items-center p-4 bg-white/5 rounded-xl cursor-pointer hover:bg-white/10 transition">
                                    <input type="checkbox" name="dns_resolution" value="1"
                                           {{ old('dns_resolution', $settings['dns_resolution'] ?? false) ? 'checked' : '' }}
                                           class="rounded border-gray-600 text-cyan-500 focus:ring-cyan-500 w-5 h-5 bg-white/10">
                                    <span class="ml-4">
                                        <span class="block text-sm font-semibold text-white">Enable DNS resolution</span>
                                        <span class="block text-xs text-gray-400 mt-1">Resolve IP addresses to hostnames (may impact performance)</span>
                                    </span>
                                </label>

                                <label class="flex items-center p-4 bg-white/5 rounded-xl cursor-pointer hover:bg-white/10 transition">
                                    <input type="checkbox" name="geolocation_enabled" value="1"
                                           {{ old('geolocation_enabled', $settings['geolocation_enabled'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-600 text-cyan-500 focus:ring-cyan-500 w-5 h-5 bg-white/10">
                                    <span class="ml-4">
                                        <span class="block text-sm font-semibold text-white">Enable IP geolocation</span>
                                        <span class="block text-xs text-gray-400 mt-1">Identify geographic location of IP addresses</span>
                                    </span>
                                </label>

                                <label class="flex items-center p-4 bg-white/5 rounded-xl cursor-pointer hover:bg-white/10 transition">
                                    <input type="checkbox" name="as_lookup_enabled" value="1"
                                           {{ old('as_lookup_enabled', $settings['as_lookup_enabled'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-600 text-cyan-500 focus:ring-cyan-500 w-5 h-5 bg-white/10">
                                    <span class="ml-4">
                                        <span class="block text-sm font-semibold text-white">Enable AS (Autonomous System) lookup</span>
                                        <span class="block text-xs text-gray-400 mt-1">Identify network ownership and BGP information</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alert Settings Section -->
                <div class="glass-card overflow-hidden" x-data="{ open: true }">
                    <button type="button" @click="open = !open" class="section-header w-full flex items-center justify-between cursor-pointer hover:bg-[var(--bg-hover)] transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-500/20 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <h3 class="text-lg font-bold text-white">Alert Thresholds</h3>
                                <p class="text-sm text-gray-400">Configure when to trigger alerts</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" x-collapse>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-300">High Traffic Threshold</label>
                                    <div class="flex items-center gap-3">
                                        <input type="number" name="traffic_threshold" value="{{ old('traffic_threshold', $settings['traffic_threshold'] ?? 1000) }}"
                                               class="flex-1 glass-input rounded-xl py-3 px-4"
                                               min="1">
                                        <span class="text-gray-400 font-medium">Mbps</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Alert when traffic exceeds this threshold</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-300">Device Offline Timeout</label>
                                    <div class="flex items-center gap-3">
                                        <input type="number" name="offline_timeout" value="{{ old('offline_timeout', $settings['offline_timeout'] ?? 5) }}"
                                               class="flex-1 glass-input rounded-xl py-3 px-4"
                                               min="1" max="60">
                                        <span class="text-gray-400 font-medium">minutes</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Mark device offline after no data received</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-300">Interface Utilization Warning</label>
                                    <div class="flex items-center gap-3">
                                        <input type="number" name="utilization_warning" value="{{ old('utilization_warning', $settings['utilization_warning'] ?? 80) }}"
                                               class="flex-1 glass-input rounded-xl py-3 px-4"
                                               min="1" max="100">
                                        <span class="text-gray-400 font-medium">%</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Warn when interface utilization exceeds this</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-300">Interface Utilization Critical</label>
                                    <div class="flex items-center gap-3">
                                        <input type="number" name="utilization_critical" value="{{ old('utilization_critical', $settings['utilization_critical'] ?? 95) }}"
                                               class="flex-1 glass-input rounded-xl py-3 px-4"
                                               min="1" max="100">
                                        <span class="text-gray-400 font-medium">%</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Critical alert when utilization exceeds this</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Info Cards -->
            <div class="space-y-6">
                <!-- Collector Quick Info -->
                <div class="glass-card overflow-hidden">
                    <div class="section-header">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Collector Configuration
                        </h3>
                    </div>

                    <div class="p-6">
                        <p class="text-sm text-gray-400 mb-4">Configure your network devices to send flow data to:</p>
                        <div class="space-y-3">
                            <div class="bg-cyan-500/20 rounded-xl p-4 border border-cyan-500/30">
                                <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Collector IP</p>
                                <p class="text-2xl font-mono font-bold text-cyan-300 mt-1">{{ $settings['collector_ip'] ?: 'Not Configured' }}</p>
                            </div>
                            <div class="bg-indigo-500/20 rounded-xl p-4 border border-indigo-500/30">
                                <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">NetFlow Port (UDP)</p>
                                <p class="text-2xl font-mono font-bold text-indigo-300 mt-1">{{ $settings['netflow_port'] ?: 'Not Configured' }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-white/5 rounded-xl p-3 border border-white/10">
                                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">sFlow Port</p>
                                    <p class="text-lg font-mono font-bold text-gray-300">{{ $settings['sflow_port'] ?: '-' }}</p>
                                </div>
                                <div class="bg-white/5 rounded-xl p-3 border border-white/10">
                                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">IPFIX Port</p>
                                    <p class="text-lg font-mono font-bold text-gray-300">{{ $settings['ipfix_port'] ?: '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configuration Examples -->
                <div class="glass-card overflow-hidden">
                    <div class="section-header">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                            Device Configuration Examples
                        </h3>
                    </div>

                    <div class="p-6" x-data="{ activeTab: 'cisco' }">
                        <div class="flex flex-wrap gap-2 mb-4">
                            <button type="button" @click="activeTab = 'cisco'" :class="activeTab === 'cisco' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-white/10 text-gray-300 hover:bg-white/20'"
                                    class="px-4 py-2 text-xs font-semibold rounded-lg transition">Cisco IOS</button>
                            <button type="button" @click="activeTab = 'fortigate'" :class="activeTab === 'fortigate' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-white/10 text-gray-300 hover:bg-white/20'"
                                    class="px-4 py-2 text-xs font-semibold rounded-lg transition">FortiGate</button>
                            <button type="button" @click="activeTab = 'paloalto'" :class="activeTab === 'paloalto' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-white/10 text-gray-300 hover:bg-white/20'"
                                    class="px-4 py-2 text-xs font-semibold rounded-lg transition">Palo Alto</button>
                            <button type="button" @click="activeTab = 'juniper'" :class="activeTab === 'juniper' ? 'bg-cyan-500 text-white shadow-lg' : 'bg-white/10 text-gray-300 hover:bg-white/20'"
                                    class="px-4 py-2 text-xs font-semibold rounded-lg transition">Juniper</button>
                        </div>

                        @if(!empty($settings['collector_ip']) && !empty($settings['netflow_port']))
                        <div x-show="activeTab === 'cisco'">
                            <pre class="bg-gray-900/80 text-emerald-400 p-4 rounded-xl text-xs overflow-x-auto max-h-48 border border-white/10">flow exporter MONETX-EXPORT
 destination {{ $settings['collector_ip'] }}
 transport udp {{ $settings['netflow_port'] }}
 export-protocol netflow-v9

flow monitor MONETX-MONITOR
 exporter MONETX-EXPORT
 record netflow ipv4 original-input

interface GigabitEthernet0/0
 ip flow monitor MONETX-MONITOR input
 ip flow monitor MONETX-MONITOR output</pre>
                        </div>

                        <div x-show="activeTab === 'fortigate'" style="display: none;">
                            <pre class="bg-gray-900/80 text-emerald-400 p-4 rounded-xl text-xs overflow-x-auto max-h-48 border border-white/10">config system netflow
    set collector-ip {{ $settings['collector_ip'] }}
    set collector-port {{ $settings['netflow_port'] }}
    set source-ip auto
end

config system interface
    edit "port1"
        set netflow-sampler both
    next
end</pre>
                        </div>

                        <div x-show="activeTab === 'paloalto'" style="display: none;">
                            <pre class="bg-gray-900/80 text-emerald-400 p-4 rounded-xl text-xs overflow-x-auto max-h-48 border border-white/10">set deviceconfig system netflow
    exporter-1 server {{ $settings['collector_ip'] }}
set deviceconfig system netflow
    exporter-1 port {{ $settings['netflow_port'] }}
set deviceconfig system netflow
    exporter-1 template-refresh-rate 20
commit</pre>
                        </div>

                        <div x-show="activeTab === 'juniper'" style="display: none;">
                            <pre class="bg-gray-900/80 text-emerald-400 p-4 rounded-xl text-xs overflow-x-auto max-h-48 border border-white/10">set services flow-monitoring version-ipfix
    template ipv4
set forwarding-options sampling instance sample
    input rate 1
set forwarding-options sampling instance sample
    family inet output
    flow-server {{ $settings['collector_ip'] }} port {{ $settings['netflow_port'] }}
    version-ipfix template ipv4</pre>
                        </div>
                        @else
                        <div class="bg-amber-500/20 border border-amber-500/30 rounded-xl p-4 text-center">
                            <svg class="w-10 h-10 text-amber-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-sm font-medium text-amber-300">Configure Collector IP and NetFlow Port first</p>
                            <p class="text-xs text-amber-400 mt-1">Device configuration examples will appear after saving settings</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- System Status -->
                <div class="glass-card overflow-hidden">
                    <div class="section-header">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                            </svg>
                            System Status
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between p-4 bg-emerald-500/10 rounded-xl border border-emerald-500/20">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                                <div>
                                    <p class="text-sm font-semibold text-white">NetFlow Collector</p>
                                    <p class="text-xs text-gray-400">Port: {{ $settings['netflow_port'] ?: 'Not Configured' }}</p>
                                </div>
                            </div>
                            <span class="badge-success">Running</span>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-emerald-500/10 rounded-xl border border-emerald-500/20">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                                <div>
                                    <p class="text-sm font-semibold text-white">Queue Worker</p>
                                    <p class="text-xs text-gray-400">Processing flows</p>
                                </div>
                            </div>
                            <span class="badge-success">Active</span>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-emerald-500/10 rounded-xl border border-emerald-500/20">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                                <div>
                                    <p class="text-sm font-semibold text-white">Scheduler</p>
                                    <p class="text-xs text-gray-400">Aggregation tasks</p>
                                </div>
                            </div>
                            <span class="badge-success">Running</span>
                        </div>

                        <div class="mt-4 pt-4 border-t border-white/10">
                            <div class="grid grid-cols-2 gap-3 text-center">
                                <div class="bg-cyan-500/20 rounded-xl p-4 border border-cyan-500/30">
                                    <p class="text-xs text-gray-400 font-semibold">Laravel</p>
                                    <p class="text-lg font-bold text-cyan-300">{{ app()->version() }}</p>
                                </div>
                                <div class="bg-indigo-500/20 rounded-xl p-4 border border-indigo-500/30">
                                    <p class="text-xs text-gray-400 font-semibold">PHP</p>
                                    <p class="text-lg font-bold text-indigo-300">{{ PHP_VERSION }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Save Footer -->
        <div class="sticky-footer">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-400">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Changes will take effect immediately after saving
                    </p>
                    <button type="submit" class="btn-primary flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Settings
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
