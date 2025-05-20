<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TankStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tank;

    public function __construct($tank)
    {
        $this->tank = $tank;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('agent.' . $this->tank->user->created_by);
    }

    public function broadcastAs()
    {
        return 'tank.status';
    }

    public function broadcastWith()
    {
        return [
            'type' => 'tank_status',
            'tank' => [
                'id' => $this->tank->id,
                'status' => $this->tank->status,
                'location' => $this->tank->location
            ]
        ];
    }
} 