@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('devices.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
            ← Back to Devices
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Add New Device</h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('devices.store') }}" method="POST" id="deviceForm">
            @csrf

            <div class="space-y-6">
                <!-- Device Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Device Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                           placeholder="e.g., MainOffice_Router" required>
                    <p class="mt-1 text-sm text-gray-500">Choose a descriptive name for the device</p>
                </div>

                <!-- IP Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        IP Address <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ip_address" value="{{ old('ip_address') }}" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                           placeholder="e.g., 192.168.1.1" required>
                    <p class="mt-1 text-sm text-gray-500">Device IP address that will send NetFlow/sFlow data</p>
                </div>

                <!-- Device Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Device Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="deviceType" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required onchange="showConfig()">
                        <option value="">Select device type...</option>
                        <option value="cisco_router" {{ old('type') === 'cisco_router' ? 'selected' : '' }}>Cisco Router</option>
                        <option value="router" {{ old('type') === 'router' ? 'selected' : '' }}>Generic Router</option>
                        <option value="switch" {{ old('type') === 'switch' ? 'selected' : '' }}>Switch</option>
                        <option value="firewall" {{ old('type') === 'firewall' ? 'selected' : '' }}>Generic Firewall</option>
                        <option value="fortigate" {{ old('type') === 'fortigate' ? 'selected' : '' }}>FortiGate Firewall</option>
                        <option value="palo_alto" {{ old('type') === 'palo_alto' ? 'selected' : '' }}>Palo Alto Firewall</option>
                        <option value="checkpoint" {{ old('type') === 'checkpoint' ? 'selected' : '' }}>Check Point Firewall</option>
                        <option value="wireless_controller" {{ old('type') === 'wireless_controller' ? 'selected' : '' }}>Wireless Controller</option>
                    </select>
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Location
                    </label>
                    <input type="text" name="location" value="{{ old('location') }}" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                           placeholder="e.g., Data Center - Rack A12">
                    <p class="mt-1 text-sm text-gray-500">Physical location of the device</p>
                </div>

                <!-- Device Group -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Device Group
                    </label>
                    <input type="text" name="device_group" value="{{ old('device_group') }}" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                           placeholder="e.g., Core Network">
                    <p class="mt-1 text-sm text-gray-500">Group or category for organizing devices</p>
                </div>

                <!-- Configuration Instructions - Dynamic based on device type -->
                <div id="configInstructions" class="bg-blue-50 border border-blue-200 rounded-lg p-6" style="display: none;">
                    <h3 class="font-semibold text-blue-900 mb-3">📋 NetFlow Configuration</h3>
                    <p class="text-sm text-blue-800 mb-3">Configure your device to send flow data to this server:</p>
                    
                    <!-- Cisco Router Config -->
                    <div id="ciscoConfig" style="display: none;">
                        <div class="bg-white rounded p-4">
                            <p class="text-xs font-semibold text-gray-700 mb-2">Cisco Router/Switch Configuration:</p>
                            <pre class="text-xs text-gray-800 overflow-x-auto">! Step 1: Create flow exporter
flow exporter NETFLOW-EXPORTER
 destination {{ request()->ip() }}
 transport udp 9995
 
! Step 2: Create flow record
flow record NETFLOW-RECORD
 match ipv4 source address
 match ipv4 destination address
 match ipv4 protocol
 match transport source-port
 match transport destination-port
 collect counter bytes
 collect counter packets
 collect timestamp absolute first
 collect timestamp absolute last
 
! Step 3: Create flow monitor
flow monitor NETFLOW-MONITOR
 record NETFLOW-RECORD
 exporter NETFLOW-EXPORTER
 
! Step 4: Apply to interfaces
interface GigabitEthernet0/0
 ip flow monitor NETFLOW-MONITOR input
 ip flow monitor NETFLOW-MONITOR output

! Verify configuration
show flow exporter
show flow monitor</pre>
                        </div>
                    </div>

                    <!-- FortiGate Config -->
                    <div id="fortiConfig" style="display: none;">
                        <div class="bg-white rounded p-4 mb-3">
                            <p class="text-xs font-semibold text-gray-700 mb-2">FortiGate CLI Configuration:</p>
                            <pre class="text-xs text-gray-800 overflow-x-auto">config system netflow
    set collector-ip {{ request()->ip() }}
    set collector-port 9995
    set source-ip 0.0.0.0
    set active-flow-timeout 30
    set inactive-flow-timeout 15
end

config system interface
    edit "port1"
        set netflow-sampler both
    next
end

! Verify configuration
diagnose test application sflowd 2</pre>
                        </div>
                        
                        <div class="bg-white rounded p-4">
                            <p class="text-xs font-semibold text-gray-700 mb-2">FortiGate GUI Configuration:</p>
                            <ol class="text-xs text-gray-800 list-decimal list-inside space-y-1">
                                <li>Go to <strong>System > Feature Visibility</strong></li>
                                <li>Enable <strong>NetFlow</strong></li>
                                <li>Go to <strong>Log & Report > Log Settings</strong></li>
                                <li>Under NetFlow, click <strong>Create New</strong></li>
                                <li>Set IP: <code>{{ request()->ip() }}</code></li>
                                <li>Set Port: <code>9995</code></li>
                                <li>Go to <strong>Policy & Objects > Firewall Policy</strong></li>
                                <li>Edit policies and enable <strong>NetFlow</strong> logging</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Palo Alto Config -->
                    <div id="paloConfig" style="display: none;">
                        <div class="bg-white rounded p-4">
                            <p class="text-xs font-semibold text-gray-700 mb-2">Palo Alto Configuration:</p>
                            <ol class="text-xs text-gray-800 list-decimal list-inside space-y-1">
                                <li>Go to <strong>Device > Server Profiles > NetFlow</strong></li>
                                <li>Click <strong>Add</strong> and name the profile</li>
                                <li>Add NetFlow Server:
                                    <ul class="ml-4 mt-1 list-disc list-inside">
                                        <li>Name: <code>NTA-Server</code></li>
                                        <li>NetFlow Server: <code>{{ request()->ip() }}</code></li>
                                        <li>Port: <code>9995</code></li>
                                    </ul>
                                </li>
                                <li>Go to <strong>Objects > Log Forwarding</strong></li>
                                <li>Create/edit profile and add NetFlow server</li>
                                <li>Apply to security policies under <strong>Actions > Log Forwarding</strong></li>
                            </ol>
                        </div>
                    </div>

                    <!-- Generic Config -->
                    <div id="genericConfig" style="display: none;">
                        <div class="bg-white rounded p-4">
                            <p class="text-xs font-semibold text-gray-700 mb-2">Generic NetFlow Configuration:</p>
                            <div class="text-xs text-gray-800 space-y-2">
                                <p><strong>Server Details:</strong></p>
                                <ul class="ml-4 list-disc list-inside">
                                    <li>NetFlow Collector IP: <code>{{ request()->ip() }}</code></li>
                                    <li>Port: <code>9995</code></li>
                                    <li>Protocol: <code>UDP</code></li>
                                    <li>Version: <code>NetFlow v5 or v9</code></li>
                                </ul>
                                <p class="mt-2"><strong>Configuration Steps:</strong></p>
                                <ol class="ml-4 list-decimal list-inside">
                                    <li>Access your device's management interface</li>
                                    <li>Navigate to NetFlow/sFlow settings</li>
                                    <li>Enable NetFlow export</li>
                                    <li>Configure collector as shown above</li>
                                    <li>Apply to desired interfaces</li>
                                    <li>Save and commit changes</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 font-medium">
                    Add Device
                </button>
                <a href="{{ route('devices.index') }}" class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-300 text-center font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function showConfig() {
    const deviceType = document.getElementById('deviceType').value;
    const configDiv = document.getElementById('configInstructions');
    
    // Hide all configs
    document.getElementById('ciscoConfig').style.display = 'none';
    document.getElementById('fortiConfig').style.display = 'none';
    document.getElementById('paloConfig').style.display = 'none';
    document.getElementById('genericConfig').style.display = 'none';
    
    if (deviceType) {
        configDiv.style.display = 'block';
        
        // Show relevant config
        if (deviceType === 'cisco_router' || deviceType === 'router' || deviceType === 'switch') {
            document.getElementById('ciscoConfig').style.display = 'block';
        } else if (deviceType === 'fortigate') {
            document.getElementById('fortiConfig').style.display = 'block';
        } else if (deviceType === 'palo_alto') {
            document.getElementById('paloConfig').style.display = 'block';
        } else {
            document.getElementById('genericConfig').style.display = 'block';
        }
    } else {
        configDiv.style.display = 'none';
    }
}
</script>
@endsection