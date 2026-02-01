<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class ActivationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $userId,
        public string $activation, // "online" | "offline"
    ) {}

    public function broadcastOn(): array
    {
        // قناة خاصة بالمستخدم ده
        return [
            new PrivateChannel("user.{$this->userId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.activation-changed';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id'    => $this->userId,
            'activation' => $this->activation,
        ];
    }
}
