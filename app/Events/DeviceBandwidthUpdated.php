<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeviceBandwidthUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $deviceId;
    public string $deviceName;
    public array $bandwidth;
    public array $sparkline;

    /**
     * Create a new event instance.
     */
    public function __construct(int $deviceId, string $deviceName, array $bandwidth, array $sparkline = [])
    {
        $this->deviceId = $deviceId;
        $this->deviceName = $deviceName;
        $this->bandwidth = $bandwidth;
        $this->sparkline = $sparkline;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard'),
            new Channel("device.{$this->deviceId}"),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'bandwidth.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'device_id' => $this->deviceId,
            'device_name' => $this->deviceName,
            'bandwidth' => $this->bandwidth,
            'sparkline' => $this->sparkline,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
