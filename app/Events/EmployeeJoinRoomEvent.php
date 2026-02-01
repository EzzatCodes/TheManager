<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployeeJoinRoomEvent implements ShouldBroadcastNow
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  /**
   * Create a new event instance.
   */
  public function __construct(
    public int $roomId,
    public int $userId,
    public string $userName,
    public string $status,      // free|busy
    public string $activation,   // online|offline
    public ?int $openedRoom,
  ) {}

  /**
   * Get the channels the event should broadcast on.
   *
   * @return array<int, \Illuminate\Broadcasting\Channel>
   */
  public function broadcastOn(): array
  {
    return [
      new PrivateChannel("room.{$this->roomId}"),
    ];
  }

  public function broadcastAs(): string
  {
    return 'employee.joined';
  }

  public function broadcastWith(): array
  {
    return [
      'user_id'    => $this->userId,
      'user_name'  => $this->userName,
      'status'     => $this->status,
      'activation' => $this->activation,
      'opened_room' => $this->openedRoom,
    ];
  }
}
