<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FlowReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $deviceId;
    public int $flowCount;
    public int $bytesReceived;

    /**
     * Create a new event instance.
     */
    public function __construct(int $deviceId, int $flowCount, int $bytesReceived)
    {
        $this->deviceId = $deviceId;
        $this->flowCount = $flowCount;
        $this->bytesReceived = $bytesReceived;
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
        return 'flow.received';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'device_id' => $this->deviceId,
            'flow_count' => $this->flowCount,
            'bytes_received' => $this->bytesReceived,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
