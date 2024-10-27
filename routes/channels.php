<?php

use App\Broadcasting\UserChannel;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Tymon\JWTAuth\Facades\JWTAuth;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

// Broadcast::channel('users', function (User $user) {
//     // Verificar que el usuario esté autenticado y tenga uno de los roles permitidos
//     return $user->hasRole('dev') || $user->hasRole('admin');
// }, ['guards' => ['auth:api']]);
// Broadcast::channel('auth', function ($user, $request) {
//     $token = $request->header('Authorization');

//     if (!$token) {
//         return false;
//     }

//     try {
//         $user = JWTAuth::parseToken($token)->authenticate();
//     } catch (\Exception $e) {
//         return false;
//     }

//     return $user;
// });

Broadcast::routes(['middleware' => ['auth:api']]);

// // Canal privado 'users' con autenticación JWT
// Broadcast::channel('users', function ($user) {
//     $token = request()->header('Authorization');

//     if (!$token) {
//         return false; // No hay token, acceso denegado
//     }

//     try {
//         $user = JWTAuth::parseToken($token)->authenticate();
//     } catch (\Exception $e) {
//         return false; // Token inválido, acceso denegado
//     }

//     return $user->is_active; // Verifica si el usuario está activo
// });

Broadcast::channel('users.{userId}', function ($user, $userId) {
    return $user->id === $userId;
});

Broadcast::channel('user.{userId}', function ($user, $userId) {
    if ($user->id === $userId) {
        return array('name' => $user->name);
    }
});