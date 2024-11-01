<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $action;

    public function __construct($userId, $action)
    {
        $this->userId = $userId;
        $this->action = $action;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('users'),
        ];
    }

    public function broadcastAs()
    {
        return 'user.deleted';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->userId,
            'action' => $this->action,
        ];
    }
}