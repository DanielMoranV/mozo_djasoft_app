<?php

use Illuminate\Support\Facades\Route;
use App\Events\TestEvent;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-event', function () {
    event(new TestEvent('Hola, esto es una prueba'));
    return 'Evento de prueba enviado';
});
