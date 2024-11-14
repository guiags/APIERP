<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\GrupoController;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('clientes', ClienteController::class)->except([
    'create', 'edit'
]);

Route::apiResource('usuarios', UsuarioController::class)->except([
    'create', 'edit'
]);

Route::apiResource('grupo', GrupoController::class)->except([
    'create', 'edit'
]);