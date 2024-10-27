<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class CustomBroadcastController extends Controller
{
    /**
     * Authenticate the request for channel access using JWT.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        try {
            // Verifica que el encabezado de autorización esté presente
            if (!$request->header('Authorization')) {
                return response()->json(['error' => 'Authorization header missing'], 401);
            }

            // Intenta autenticar al usuario usando el token JWT
            $user = JWTAuth::parseToken()->authenticate();

            // Si el usuario es válido, pasa el usuario autenticado al broadcast
            return Broadcast::auth($request->merge(['user' => $user]));
        } catch (TokenExpiredException $e) {
            // Token expirado
            Log::error('Token expired: ' . $e->getMessage());
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (TokenInvalidException $e) {
            // Token inválido
            Log::error('Invalid token: ' . $e->getMessage());
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (JWTException $e) {
            // Token no encontrado
            Log::error('Token not provided: ' . $e->getMessage());
            return response()->json(['error' => 'Token not provided'], 401);
        } catch (\Exception $e) {
            // Otro error (general)
            Log::info('Authorization token: ' . $request->header('Authorization'));
            Log::error('Authentication error: ' . $e->getMessage(), ['exception' => $e]);
            $errorMessage = $e->getMessage() ?: 'An unknown error occurred during authentication';
            return response()->json(['error' => 'Unauthorized access', 'message' => $errorMessage], 403);
        }
    }
}
