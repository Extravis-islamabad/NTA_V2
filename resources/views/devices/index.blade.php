@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-white">Device Inventory</h2>
        <a href="{{ route('devices.create') }}" class="btn-monetx text-white px-6 py-2 rounded-lg hover:opacity-90 inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Device
        </a>
    </div>

    @if(session('success'))
        <div class="glass-card bg-green-500/20 border-green-500/30 text-green-300 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($devices->isEmpty())
        <div class="glass-card p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-white">No Devices Found</h3>
            <p class="mt-2 text-sm text-gray-400">Get started by adding your first network device.</p>
            <div class="mt-6">
                <a href="{{ route('devices.create') }}" class="inline-flex items-center px-6 py-3 btn-monetx text-white font-medium rounded-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Your First Device
                </a>
            </div>
        </div>
    @else
        <div class="glass-card overflow-hidden">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-purple-500/20">
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">Device Name</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">IP Address</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">Location</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">Interfaces</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">Flows</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-purple-300 uppercase tracking-wider bg-purple-500/10">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($devices as $device)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('devices.show', $device) }}" class="text-purple-400 hover:text-purple-300 font-medium">
                                {{ $device->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $device->ip_address }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            {{ ucfirst(str_replace('_', ' ', $device->type)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            {{ $device->location ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            {{ $device->interface_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            {{ number_format($device->flow_count) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($device->status === 'online')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-500/20 text-green-400 border border-green-500/30">
                                    Online
                                </span>
                            @elseif($device->status === 'warning')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                                    Warning
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-500/20 text-red-400 border border-red-500/30">
                                    Offline
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('devices.show', $device) }}" class="text-purple-400 hover:text-purple-300 mr-3">
                                View
                            </a>
                            <a href="{{ route('devices.edit', $device) }}" class="text-green-400 hover:text-green-300 mr-3">
                                Edit
                            </a>
                            <button onclick="deleteDevice({{ $device->id }})" class="text-red-400 hover:text-red-300">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@push('scripts')
<script>
function deleteDevice(deviceId) {
    if (confirm('Are you sure you want to delete this device? This will also delete all associated flows and interfaces.')) {
        fetch(`/devices/${deviceId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting device');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting device');
        });
    }
}
</script>
@endpush
@endsection
