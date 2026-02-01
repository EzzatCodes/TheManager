<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeleteEmployeeFromRoomEvent implements ShouldBroadcastNow 
{
  use Dispatchable, InteractsWithSockets, SerializesModels;


  public $roomId;
  public $userId;
  public $userName;


  /**
   * Create a new event instance.
   */
  public function __construct($roomId, $userId, $userName)
  {
    $this->roomId = $roomId;
    $this->userId = $userId;
    $this->userName = $userName;
  }

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

  public function broadcastAs()
  {
    return 'employee.removed';
  }

  public function broadcastWith () {
    return [
      'user_id' => $this->userId,
      'user_name' => $this->userName,
    ];
  }
}
