<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configura el middleware auth:api para proteger las rutas de broadcast
        Broadcast::routes(['middleware' => ['auth:api']]);

        // Carga las definiciones de canales
        require base_path('routes/channels.php');
    }
}