<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LotteryUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $winners;

    public function __construct(Room $room, $winners)
    {
        $this->room = $room;
        $this->winners = $winners;
    }

    public function broadcastOn()
    {
        return new Channel('lottery-room.' . $this->room->id);
    }

    public function broadcastWith()
    {
        return [
            'channel' => 'lottery-room.' . $this->room->id,
            'winners' => $this->winners,
        ];
    }
}
