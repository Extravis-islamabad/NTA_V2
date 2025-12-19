@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('devices.index') }}" class="text-cyan-400 hover:text-cyan-300 flex items-center gap-2 text-sm font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Devices
        </a>
    </div>

    <!-- Header with Logo -->
    <div class="flex items-center gap-4 mb-6">
        <div class="w-14 h-14 bg-cyan-500/20 border border-cyan-500/30 rounded-2xl flex items-center justify-center">
            <svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
        </div>
        <div>
            <div class="flex items-center gap-3">
                <img src="{{ asset('MonetX_white@4x-8.png') }}" alt="MonetX" class="h-7 w-auto">
                <span class="text-gray-500 text-xl">|</span>
                <h2 class="text-2xl font-bold text-white">Add New Device</h2>
            </div>
            <p class="text-gray-400 mt-1">Configure a network device to send NetFlow data</p>
        </div>
    </div>

    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="section-header">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                </svg>
                Device Configuration
            </h3>
            <p class="text-gray-400 text-sm mt-1">Fill in the device details and optionally enable SSH for automatic configuration</p>
        </div>

        @if($errors->any())
            <div class="bg-red-500/20 border-l-4 border-red-500 text-red-300 px-4 py-3 m-6 rounded-r-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('devices.store') }}" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="border-b border-white/10 pb-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-cyan-500/20 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                        </svg>
                    </span>
                    Device Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Device Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full glass-input rounded-lg py-3 px-4"
                               placeholder="e.g., Core-Router-01" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">IP Address <span class="text-red-400">*</span></label>
                        <input type="text" name="ip_address" value="{{ old('ip_address') }}"
                               class="w-full glass-input rounded-lg py-3 px-4"
                               placeholder="e.g., 192.168.1.1" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Device Type <span class="text-red-400">*</span></label>
                        <select name="type" id="deviceType" class="w-full glass-input rounded-lg py-3 px-4" required onchange="showConfig()">
                            <option value="">Select device type...</option>
                            <option value="cisco_router" {{ old('type') == 'cisco_router' ? 'selected' : '' }}>Cisco Router</option>
                            <option value="router" {{ old('type') == 'router' ? 'selected' : '' }}>Generic Router</option>
                            <option value="switch" {{ old('type') == 'switch' ? 'selected' : '' }}>Switch</option>
                            <option value="firewall" {{ old('type') == 'firewall' ? 'selected' : '' }}>Firewall</option>
                            <option value="fortigate" {{ old('type') == 'fortigate' ? 'selected' : '' }}>FortiGate</option>
                            <option value="palo_alto" {{ old('type') == 'palo_alto' ? 'selected' : '' }}>Palo Alto</option>
                            <option value="checkpoint" {{ old('type') == 'checkpoint' ? 'selected' : '' }}>Check Point</option>
                            <option value="wireless_controller" {{ old('type') == 'wireless_controller' ? 'selected' : '' }}>Wireless Controller</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}"
                               class="w-full glass-input rounded-lg py-3 px-4"
                               placeholder="e.g., Data Center A">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Device Group</label>
                        <input type="text" name="device_group" value="{{ old('device_group') }}"
                               class="w-full glass-input rounded-lg py-3 px-4"
                               placeholder="e.g., Core Network">
                    </div>
                </div>
            </div>

            <!-- SSH Configuration -->
            <div class="border-b border-white/10 pb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <span class="w-8 h-8 bg-indigo-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        SSH Configuration
                    </h3>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="ssh_enabled" id="ssh_enabled" value="1"
                               class="rounded border-gray-600 text-cyan-500 focus:ring-cyan-500 bg-white/10"
                               {{ old('ssh_enabled') ? 'checked' : '' }}
                               onchange="toggleSshFields()">
                        <span class="ml-2 text-sm text-gray-400">Enable SSH</span>
                    </label>
                </div>

                <div id="ssh_fields" class="hidden space-y-4">
                    <div class="bg-indigo-500/10 border border-indigo-500/30 rounded-lg p-4">
                        <p class="text-sm text-indigo-300">
                            <strong>Note:</strong> SSH credentials are encrypted and stored securely. Enable SSH to allow automatic NetFlow configuration push to this device.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">SSH Host</label>
                            <input type="text" name="ssh_host" value="{{ old('ssh_host') }}"
                                   class="w-full glass-input rounded-lg py-3 px-4"
                                   placeholder="Leave empty to use device IP">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">SSH Port</label>
                            <input type="number" name="ssh_port" value="{{ old('ssh_port', 22) }}"
                                   class="w-full glass-input rounded-lg py-3 px-4"
                                   min="1" max="65535">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Username</label>
                            <input type="text" name="ssh_username" value="{{ old('ssh_username') }}"
                                   class="w-full glass-input rounded-lg py-3 px-4"
                                   placeholder="admin">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                            <input type="password" name="ssh_password"
                                   class="w-full glass-input rounded-lg py-3 px-4"
                                   placeholder="••••••••">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Private Key (Optional)</label>
                            <textarea name="ssh_private_key" rows="4"
                                      class="w-full glass-input rounded-lg py-3 px-4 font-mono text-sm"
                                      placeholder="-----BEGIN OPENSSH PRIVATE KEY-----">{{ old('ssh_private_key') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Paste your SSH private key for key-based authentication</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SNMP Configuration -->
            <div class="border-b border-white/10 pb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <span class="w-8 h-8 bg-emerald-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </span>
                        SNMP Configuration
                    </h3>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="snmp_enabled" id="snmp_enabled" value="1"
                               class="rounded border-gray-600 text-cyan-500 focus:ring-cyan-500 bg-white/10"
                               {{ old('snmp_enabled') ? 'checked' : '' }}
                               onchange="toggleSnmpFields()">
                        <span class="ml-2 text-sm text-gray-400">Enable SNMP</span>
                    </label>
                </div>

                <div id="snmp_fields" class="hidden space-y-4">
                    <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-lg p-4">
                        <p class="text-sm text-emerald-300">
                            <strong>Note:</strong> SNMP credentials are encrypted and stored securely. Enable SNMP to poll interface statistics, bandwidth utilization, and device health.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">SNMP Version</label>
                            <select name="snmp_version" id="snmp_version"
                                    class="w-full glass-input rounded-lg py-3 px-4"
                                    onchange="toggleSnmpVersion()">
                                <option value="v2c" {{ old('snmp_version', 'v2c') === 'v2c' ? 'selected' : '' }}>SNMPv2c</option>
                                <option value="v1" {{ old('snmp_version') === 'v1' ? 'selected' : '' }}>SNMPv1</option>
                                <option value="v3" {{ old('snmp_version') === 'v3' ? 'selected' : '' }}>SNMPv3</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">SNMP Port</label>
                            <input type="number" name="snmp_port" value="{{ old('snmp_port', 161) }}"
                                   class="w-full glass-input rounded-lg py-3 px-4"
                                   min="1" max="65535">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Poll Interval (seconds)</label>
                            <input type="number" name="snmp_poll_interval" value="{{ old('snmp_poll_interval', 300) }}"
                                   class="w-full glass-input rounded-lg py-3 px-4"
                                   min="60" max="3600">
                        </div>
                    </div>

                    <!-- SNMPv1/v2c Community String -->
                    <div id="snmp_community_fields">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Community String</label>
                        <input type="password" name="snmp_community" value="{{ old('snmp_community') }}"
                               class="w-full glass-input rounded-lg py-3 px-4"
                               placeholder="public">
                        <p class="mt-1 text-xs text-gray-500">Read-only community string for polling</p>
                    </div>

                    <!-- SNMPv3 Security Settings -->
                    <div id="snmp_v3_fields" class="hidden space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Username</label>
                                <input type="text" name="snmp_username" value="{{ old('snmp_username') }}"
                                       class="w-full glass-input rounded-lg py-3 px-4"
                                       placeholder="snmpuser">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Security Level</label>
                                <select name="snmp_security_level"
                                        class="w-full glass-input rounded-lg py-3 px-4">
                                    <option value="authPriv" {{ old('snmp_security_level', 'authPriv') === 'authPriv' ? 'selected' : '' }}>authPriv (Auth + Encryption)</option>
                                    <option value="authNoPriv" {{ old('snmp_security_level') === 'authNoPriv' ? 'selected' : '' }}>authNoPriv (Auth only)</option>
                                    <option value="noAuthNoPriv" {{ old('snmp_security_level') === 'noAuthNoPriv' ? 'selected' : '' }}>noAuthNoPriv (No security)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Auth Protocol</label>
                                <select name="snmp_auth_protocol"
                                        class="w-full glass-input rounded-lg py-3 px-4">
                                    <option value="SHA" {{ old('snmp_auth_protocol', 'SHA') === 'SHA' ? 'selected' : '' }}>SHA</option>
                                    <option value="SHA256" {{ old('snmp_auth_protocol') === 'SHA256' ? 'selected' : '' }}>SHA-256</option>
                                    <option value="SHA512" {{ old('snmp_auth_protocol') === 'SHA512' ? 'selected' : '' }}>SHA-512</option>
                                    <option value="MD5" {{ old('snmp_auth_protocol') === 'MD5' ? 'selected' : '' }}>MD5</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Auth Password</label>
                                <input type="password" name="snmp_auth_password"
                                       class="w-full glass-input rounded-lg py-3 px-4"
                                       placeholder="••••••••">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Privacy Protocol</label>
                                <select name="snmp_priv_protocol"
                                        class="w-full glass-input rounded-lg py-3 px-4">
                                    <option value="AES" {{ old('snmp_priv_protocol', 'AES') === 'AES' ? 'selected' : '' }}>AES-128</option>
                                    <option value="AES192" {{ old('snmp_priv_protocol') === 'AES192' ? 'selected' : '' }}>AES-192</option>
                                    <option value="AES256" {{ old('snmp_priv_protocol') === 'AES256' ? 'selected' : '' }}>AES-256</option>
                                    <option value="DES" {{ old('snmp_priv_protocol') === 'DES' ? 'selected' : '' }}>DES</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Privacy Password</label>
                                <input type="password" name="snmp_priv_password"
                                       class="w-full glass-input rounded-lg py-3 px-4"
                                       placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $collectorIp = \App\Models\Setting::get('collector_ip') ?: 'Not Configured';
                $netflowPort = \App\Models\Setting::get('netflow_port') ?: 'Not Configured';
            @endphp

            <!-- NetFlow Configuration Info -->
            <div id="configInstructions" class="bg-cyan-500/10 border border-cyan-500/30 rounded-xl p-6" style="display: none;">
                <h3 class="text-lg font-semibold text-white mb-3">NetFlow Configuration</h3>
                <div class="bg-[var(--bg-input)] rounded-lg p-4 border border-white/10 mb-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-400">Collector IP:</span>
                            <span class="font-mono font-medium text-cyan-400 ml-2">{{ $collectorIp }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Collector Port:</span>
                            <span class="font-mono font-medium text-cyan-400 ml-2">{{ $netflowPort }} (UDP)</span>
                        </div>
                    </div>
                </div>

                <!-- Cisco Config -->
                <div id="ciscoConfig" class="hidden">
                    <pre class="bg-gray-900 text-emerald-400 p-4 rounded-lg text-xs overflow-x-auto border border-white/10">flow exporter NETFLOW-EXPORTER
 destination {{ $collectorIp }}
 transport udp {{ $netflowPort }}

flow monitor NETFLOW-MONITOR
 exporter NETFLOW-EXPORTER
 record netflow ipv4 original-input

interface GigabitEthernet0/0
 ip flow monitor NETFLOW-MONITOR input
 ip flow monitor NETFLOW-MONITOR output</pre>
                </div>

                <!-- FortiGate Config -->
                <div id="fortiConfig" class="hidden">
                    <pre class="bg-gray-900 text-emerald-400 p-4 rounded-lg text-xs overflow-x-auto border border-white/10">config system netflow
    set collector-ip {{ $collectorIp }}
    set collector-port {{ $netflowPort }}
end

config system interface
    edit "port1"
        set netflow-sampler both
    next
end</pre>
                </div>

                <!-- Palo Alto Config -->
                <div id="paloConfig" class="hidden">
                    <pre class="bg-gray-900 text-emerald-400 p-4 rounded-lg text-xs overflow-x-auto border border-white/10">set deviceconfig system netflow exporter-1 server {{ $collectorIp }}
set deviceconfig system netflow exporter-1 port {{ $netflowPort }}
commit</pre>
                </div>

                <!-- Generic Config -->
                <div id="genericConfig" class="hidden">
                    <div class="bg-[var(--bg-input)] rounded-lg p-4 text-sm border border-white/10">
                        <p class="font-medium text-white mb-2">Configure your device with:</p>
                        <ul class="list-disc list-inside text-gray-400 space-y-1">
                            <li>Collector IP: <code class="bg-white/10 px-1 rounded text-cyan-400">{{ $collectorIp }}</code></li>
                            <li>Port: <code class="bg-white/10 px-1 rounded text-cyan-400">{{ $netflowPort }}</code></li>
                            <li>Protocol: <code class="bg-white/10 px-1 rounded text-cyan-400">UDP</code></li>
                            <li>Version: <code class="bg-white/10 px-1 rounded text-cyan-400">NetFlow v5 or v9</code></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4 pt-4">
                <a href="{{ route('devices.index') }}"
                   class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Device
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleSshFields() {
    const checkbox = document.getElementById('ssh_enabled');
    const fields = document.getElementById('ssh_fields');
    fields.classList.toggle('hidden', !checkbox.checked);
}

function toggleSnmpFields() {
    const checkbox = document.getElementById('snmp_enabled');
    const fields = document.getElementById('snmp_fields');
    fields.classList.toggle('hidden', !checkbox.checked);
    if (checkbox.checked) {
        toggleSnmpVersion();
    }
}

function toggleSnmpVersion() {
    const version = document.getElementById('snmp_version').value;
    const communityFields = document.getElementById('snmp_community_fields');
    const v3Fields = document.getElementById('snmp_v3_fields');

    if (version === 'v3') {
        communityFields.classList.add('hidden');
        v3Fields.classList.remove('hidden');
    } else {
        communityFields.classList.remove('hidden');
        v3Fields.classList.add('hidden');
    }
}

function showConfig() {
    const deviceType = document.getElementById('deviceType').value;
    const configDiv = document.getElementById('configInstructions');

    document.getElementById('ciscoConfig').classList.add('hidden');
    document.getElementById('fortiConfig').classList.add('hidden');
    document.getElementById('paloConfig').classList.add('hidden');
    document.getElementById('genericConfig').classList.add('hidden');

    if (deviceType) {
        configDiv.style.display = 'block';

        if (deviceType === 'cisco_router' || deviceType === 'router' || deviceType === 'switch') {
            document.getElementById('ciscoConfig').classList.remove('hidden');
        } else if (deviceType === 'fortigate') {
            document.getElementById('fortiConfig').classList.remove('hidden');
        } else if (deviceType === 'palo_alto') {
            document.getElementById('paloConfig').classList.remove('hidden');
        } else {
            document.getElementById('genericConfig').classList.remove('hidden');
        }
    } else {
        configDiv.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleSshFields();
    toggleSnmpFields();
    showConfig();
});
</script>
@endsection
