<?php

namespace App\Events;

use App\Models\Location;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Location $location,
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('locations'),
        ];
    }

    public function broadcastWith()
    {
        // Retourne les données que tu souhaites transmettre
        return [
            'id' => $this->location->id,
            'name' => $this->location->name,
            'description' => $this->location->description,
            'latitude' => $this->location->latitude,
            'longitude' => $this->location->longitude,
            'is_featured' => $this->location->is_featured,
        ];
    }
}
