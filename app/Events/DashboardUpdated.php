<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $stats;
    public array $topApplications;
    public array $trafficByCountry;

    /**
     * Create a new event instance.
     */
    public function __construct(array $stats, array $topApplications = [], array $trafficByCountry = [])
    {
        $this->stats = $stats;
        $this->topApplications = $topApplications;
        $this->trafficByCountry = $trafficByCountry;
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
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'dashboard.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'stats' => $this->stats,
            'top_applications' => $this->topApplications,
            'traffic_by_country' => $this->trafficByCountry,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
