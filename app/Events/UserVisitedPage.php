<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserVisitedPage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $roomId;
    public $accessNumber;

    public function __construct($roomId, $accessNumber)
    {
        $this->roomId = $roomId;
        $this->accessNumber = $accessNumber;
    }

    public function broadcastOn()
    {
        return new Channel('lottery-room.' . $this->roomId);
    }

    public function broadcastWith()
    {
        return [
            'accessNumber' => $this->accessNumber
        ];
    }
}
