<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class UserDetailEvent implements ShouldBroadcast
{
    public $user;

    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new Channel('home');
    }

    public function broadcastWith()
    {
        return ['user' => $this->user];
    }
}
