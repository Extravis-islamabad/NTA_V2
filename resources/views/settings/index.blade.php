@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Settings</h2>
        <p class="text-gray-600">Configure your NetFlow analyzer settings</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <!-- NetFlow Port -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        NetFlow Listener Port
                    </label>
                    <input type="number" name="netflow_port" value="{{ old('netflow_port', $settings['netflow_port']) }}" 
                           class="w-full border-gray-300 rounded-md" required min="1024" max="65535">
                    <p class="mt-1 text-sm text-gray-500">UDP port for receiving NetFlow packets (default: 9995)</p>
                    @error('netflow_port')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Retention Days -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Data Retention (Days)
                    </label>
                    <input type="number" name="retention_days" value="{{ old('retention_days', $settings['retention_days']) }}" 
                           class="w-full border-gray-300 rounded-md" required min="1" max="365">
                    <p class="mt-1 text-sm text-gray-500">Number of days to retain flow data</p>
                    @error('retention_days')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Aggregation -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="aggregation_enabled" value="1" 
                               {{ old('aggregation_enabled', $settings['aggregation_enabled']) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600">
                        <span class="ml-2 text-sm text-gray-700">Enable automatic traffic aggregation</span>
                    </label>
                    <p class="mt-1 ml-6 text-sm text-gray-500">Automatically aggregate traffic data for reporting</p>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">NetFlow Configuration</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <h4 class="font-medium text-gray-900 mb-2">Configure your network devices:</h4>
                        <pre class="text-sm text-gray-700">
# Cisco Router/Switch
flow exporter NETFLOW-EXPORTER
 destination {{ request()->ip() }}
 transport udp {{ $settings['netflow_port'] }}
 
# Configure NetFlow on interface
interface GigabitEthernet0/1
 ip flow monitor NETFLOW-MONITOR input
 ip flow monitor NETFLOW-MONITOR output</pre>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                    Save Settings
                </button>
            </div>
        </form>
    </div>

    <!-- System Info -->
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">System Information</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Laravel Version</p>
                <p class="font-medium">{{ app()->version() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">PHP Version</p>
                <p class="font-medium">{{ PHP_VERSION }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Database</p>
                <p class="font-medium">{{ config('database.default') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Server IP</p>
                <p class="font-medium">{{ request()->ip() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection