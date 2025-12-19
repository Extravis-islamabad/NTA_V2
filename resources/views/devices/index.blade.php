@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-white">Device Inventory</h2>
            <p class="text-sm text-purple-300/60 mt-1">Manage and monitor your network devices</p>
        </div>
        <a href="{{ route('devices.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-500 hover:to-purple-400 text-white font-medium rounded-lg transition-all shadow-lg shadow-purple-500/25">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Device
        </a>
    </div>

    @if(session('success'))
        <div class="glass-card bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-lg mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($devices->isEmpty())
        <div class="glass-card p-12 text-center min-h-[400px] flex flex-col items-center justify-center">
            <div class="w-20 h-20 rounded-full bg-purple-500/10 flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-purple-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">No Devices Found</h3>
            <p class="text-purple-300/60 mb-6 max-w-md">Get started by adding your first network device to begin monitoring traffic flows.</p>
            <a href="{{ route('devices.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-500 hover:to-purple-400 text-white font-medium rounded-lg transition-all shadow-lg shadow-purple-500/25">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Your First Device
            </a>
        </div>
    @else
        <!-- Search and Filters -->
        <div class="glass-card p-4 mb-4" x-data="{
            search: '{{ request('search') }}',
            type: '{{ request('type') }}',
            status: '{{ request('status') }}'
        }">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <!-- Search -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs text-purple-300/60 uppercase tracking-wider mb-1.5">Search</label>
                    <div class="relative">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-purple-300/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search devices..."
                            class="w-full pl-10 pr-4 py-2 glass-input rounded-lg text-sm text-white placeholder-purple-300/40">
                    </div>
                </div>

                <!-- Type Filter -->
                <div class="w-40">
                    <label class="block text-xs text-purple-300/60 uppercase tracking-wider mb-1.5">Type</label>
                    <select name="type" class="w-full px-3 py-2 glass-input rounded-lg text-sm text-white">
                        <option value="">All Types</option>
                        <option value="router" {{ request('type') === 'router' ? 'selected' : '' }}>Router</option>
                        <option value="switch" {{ request('type') === 'switch' ? 'selected' : '' }}>Switch</option>
                        <option value="firewall" {{ request('type') === 'firewall' ? 'selected' : '' }}>Firewall</option>
                        <option value="server" {{ request('type') === 'server' ? 'selected' : '' }}>Server</option>
                        <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="w-36">
                    <label class="block text-xs text-purple-300/60 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2 glass-input rounded-lg text-sm text-white">
                        <option value="">All Status</option>
                        <option value="online" {{ request('status') === 'online' ? 'selected' : '' }}>Online</option>
                        <option value="offline" {{ request('status') === 'offline' ? 'selected' : '' }}>Offline</option>
                        <option value="warning" {{ request('status') === 'warning' ? 'selected' : '' }}>Warning</option>
                    </select>
                </div>

                <!-- Filter Button -->
                <button type="submit" class="px-4 py-2 bg-purple-500/20 hover:bg-purple-500/30 border border-purple-500/30 text-purple-300 text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>

                @if(request('search') || request('type') || request('status'))
                <a href="{{ route('devices.index') }}" class="px-4 py-2 text-purple-300/60 hover:text-purple-300 text-sm transition-colors">
                    Clear
                </a>
                @endif
            </form>
        </div>

        <!-- Device Count -->
        <div class="mb-4 text-sm text-purple-300/60">
            Showing {{ $devices->count() }} device{{ $devices->count() !== 1 ? 's' : '' }}
        </div>

        <!-- Device Table -->
        <div class="glass-card overflow-hidden min-h-[400px]">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-purple-500/20">
                        <th class="px-5 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider bg-purple-500/10">Device</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider bg-purple-500/10">IP Address</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider bg-purple-500/10">Type</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider bg-purple-500/10">Location</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider bg-purple-500/10">Interfaces</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider bg-purple-500/10">Flows</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-purple-300/70 uppercase tracking-wider bg-purple-500/10">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-purple-300/70 uppercase tracking-wider bg-purple-500/10">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-500/10">
                    @foreach($devices as $index => $device)
                    <tr class="{{ $index % 2 === 0 ? 'bg-purple-500/5' : '' }} hover:bg-purple-500/10 transition-colors group">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-purple-500/10 border border-purple-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                                    </svg>
                                </div>
                                <div>
                                    <a href="{{ route('devices.show', $device) }}" class="text-white font-medium hover:text-purple-300 transition-colors">
                                        {{ $device->name }}
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm text-purple-300/80">{{ $device->ip_address }}</span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-purple-500/20 text-purple-300 border border-purple-500/20">
                                {{ ucfirst(str_replace('_', ' ', $device->type)) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-sm text-purple-300/60">
                            {{ $device->location ?? '-' }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-sm text-white font-medium">
                            {{ $device->interface_count }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-sm text-white font-medium">
                            {{ number_format($device->flow_count) }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($device->status === 'online')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    Online
                                </span>
                            @elseif($device->status === 'warning')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                    Warning
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-red-500/20 text-red-400 border border-red-500/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                    Offline
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('devices.show', $device) }}" class="p-2 hover:bg-purple-500/20 rounded-lg transition-colors" title="View Details">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('devices.edit', $device) }}" class="p-2 hover:bg-emerald-500/20 rounded-lg transition-colors" title="Edit Device">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button onclick="deleteDevice({{ $device->id }}, '{{ addslashes($device->name) }}')" class="p-2 hover:bg-red-500/20 rounded-lg transition-colors" title="Delete Device">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($devices instanceof \Illuminate\Pagination\LengthAwarePaginator && $devices->hasPages())
        <div class="mt-4">
            {{ $devices->appends(request()->query())->links() }}
        </div>
        @endif
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="glass-card p-6 max-w-md w-full relative">
            <h3 class="text-lg font-semibold text-white mb-2">Delete Device</h3>
            <p class="text-purple-300/70 text-sm mb-4">
                Are you sure you want to delete <span id="deleteDeviceName" class="text-white font-medium"></span>?
                This will also delete all associated flows and interfaces.
            </p>
            <div class="flex justify-end gap-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-purple-300/70 hover:text-purple-300 text-sm font-medium transition-colors">
                    Cancel
                </button>
                <button onclick="confirmDelete()" class="px-4 py-2 bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-red-400 text-sm font-medium rounded-lg transition-colors">
                    Delete Device
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let deleteDeviceId = null;

function deleteDevice(deviceId, deviceName) {
    deleteDeviceId = deviceId;
    document.getElementById('deleteDeviceName').textContent = deviceName;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteDeviceId = null;
}

function confirmDelete() {
    if (!deleteDeviceId) return;

    fetch(`/devices/${deleteDeviceId}`, {
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
    })
    .finally(() => {
        closeDeleteModal();
    });
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection
