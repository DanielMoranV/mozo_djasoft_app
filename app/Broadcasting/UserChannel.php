<?php

namespace App\Broadcasting;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join($user): array|bool|User
    {
        if ($user->hasRole('dev') || $user->hasRole('admin')) {
            Log::info('Usuario autenticado:', ['user_id' => $user->id]);
            return $user; // Permitir acceso
        }
        return false;
    }
}