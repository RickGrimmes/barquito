<?php
namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class UserDetailEvent implements ShouldBroadcast
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function broadcastOn()
    {
        return new Channel('home');
    }

    public function broadcastWith()
    {
        // Encuentra al usuario por el token
        $user = User::where('token', $this->token)->first();

        // Si el usuario no existe, devuelve un array vacío
        if ($user == null) {
            return [];
        }

        // Si el usuario existe, devuelve sus detalles
        return ['user' => $user];
    }
}
