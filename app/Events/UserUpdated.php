<?php

namespace App\Events;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth; // Agregar esta línea al inicio del archivo

class UserUpdated implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $emit_user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->emit_user = Auth::id(); // Obtener el ID del usuario autenticado
    }

    public function broadcastOn(): array
    {
        // Usamos PrivateChannel en lugar de Channel
        return [
            new PrivateChannel('users'),
        ];
    }

    public function broadcastAs()
    {
        return 'user.updated';
    }

    public function broadcastWith()
    {
        return array_merge((new UserResource($this->user))->toArray(request()), ['emit_user' => $this->emit_user]); // Convertir UserResource a array
    }
}