<div class="space-y-4">
    @forelse($device->interfaces as $interface)
    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h4 class="text-lg font-semibold text-gray-900">{{ $interface->name }}</h4>
                @if($interface->description)
                <p class="text-sm text-gray-500">{{ $interface->description }}</p>
                @endif
            </div>
            <span class="px-3 py-1 text-xs font-semibold rounded {{ $interface->status === 'up' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ ucfirst($interface->status) }}
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-500">Type</p>
                <p class="font-medium text-gray-900">{{ ucfirst($interface->type) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Speed</p>
                <p class="font-medium text-gray-900">{{ $interface->formatted_speed }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">In Octets</p>
                <p class="font-medium text-gray-900">{{ number_format($interface->in_octets) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Out Octets</p>
                <p class="font-medium text-gray-900">{{ number_format($interface->out_octets) }}</p>
            </div>
        </div>

        <div class="mt-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-gray-600">Utilization</span>
                <span class="text-sm font-semibold text-gray-900">{{ number_format($interface->utilization_percent, 2) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="h-3 rounded-full {{ $interface->utilization_percent > 80 ? 'bg-red-600' : ($interface->utilization_percent > 60 ? 'bg-yellow-500' : 'bg-green-600') }}" 
                     style="width: {{ min(100, $interface->utilization_percent) }}%"></div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-12">
        <p class="text-gray-500">No interfaces configured</p>
    </div>
    @endforelse
</div>