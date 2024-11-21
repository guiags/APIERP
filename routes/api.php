<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\GrupoController;
use App\Http\Controllers\Api\PlanopagController;
use App\Http\Controllers\Api\FormapagController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\ConfiguracaoController;
use App\Http\Controllers\Api\ConfigvendedorController;
use App\Http\Controllers\Api\ProdutoController;

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

Route::apiResource('planospag', PlanopagController::class)->except([
    'create', 'edit'
]);

Route::apiResource('formaspag', FormapagController::class)->except([
    'create', 'edit'
]);

Route::apiResource('empresa', EmpresaController::class)->except([
    'create', 'edit'
]);

Route::apiResource('configuracao', ConfiguracaoController::class)->except([
    'create', 'edit'
]);

Route::apiResource('configvendedor', ConfigvendedorController::class)->except([
    'create', 'edit'
]);

Route::apiResource('produtos', ProdutoController::class)->except([
    'create', 'edit'
]);


Route::get('clientes/cpfcnpj/{cpfcnpj}', [ClienteController::class, 'showByCpfCnpj']);

Route::get('usuarios/idvendedor/{idvendedor}', [UsuarioController::class, 'showByIdVendedor']);