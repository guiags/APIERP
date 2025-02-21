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
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\HistpedidoController;
use App\Http\Controllers\Api\CtreceberController;
use App\Http\Controllers\Api\DashboardvendasController;
use App\Http\Controllers\Api\ProdutoimagemController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\Api\NavegadorRequestController;
use App\Http\Controllers\Api\LotesController;

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

Route::apiResource('pedido', PedidoController::class)->except([
    'create', 'edit'
]);
Route::apiResource('hist_pedido', HistpedidoController::class)->except([
    'create', 'edit'
]);
Route::apiResource('ctreceber', CtreceberController::class)->except([
    'create', 'edit'
]);
Route::apiResource('dashboard_vendas', DashboardvendasController::class)->except([
    'create', 'edit', 'destroy', 'update', 'index'
]);

Route::apiResource('produtosimagens', ProdutoimagemController::class)->except([
    'create', 'edit', 'index', 'update'
]);


Route::get('clientes/cpfcnpj/{cpfcnpj}', [ClienteController::class, 'showByCpfCnpj']);
Route::delete('clientes/cpfcnpj/{cpfcnpj}', [ClienteController::class, 'destroyByCpfCnpj']);
Route::put('clientes/cpfcnpj/{cpfcnpj}', [ClienteController::class, 'updateByCpfCnpj']);

Route::get('usuarios/idvendedor/{idvendedor}', [UsuarioController::class, 'showByIdVendedor']);
Route::delete('usuarios/idvendedor/{idvendedor}', [UsuarioController::class, 'destroyByIdVendedor']);
Route::put('usuarios/idvendedor/{idvendedor}', [UsuarioController::class, 'updateByIdVendedor']);

Route::delete('deletar/ctreceber/', [CtreceberController::class, 'destroyAll']);

Route::delete('pedido/idvendedor/{idvendedor}', [PedidoController::class, 'destroyByIdVendedor']);
Route::get('ultimo/pedido/', [PedidoController::class, 'showUltimoPedido']);

Route::get('produtos/lotes/{codprod}', [ProdutoController::class, 'showProdutosByCodprod']);

Route::post('gerar-relatorio', [RelatorioController::class, 'gerarRelatorio']);


Route::middleware(['compress.response'])->group(function () {
    Route::get('teste/produtos', [ProdutoController::class, 'index']);
});

Route::get('viaurl/produtos/', [NavegadorRequestController::class, 'indexNavegador']);

Route::post('lote/produtos/', [LotesController::class, 'loteProdutos']);

Route::post('lote/clientes/', [LotesController::class, 'loteClientes']);