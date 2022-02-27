<?php

use App\Http\Controllers\GrupoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('jwt.auth')->group(function () {
    /*     -- Rotas protegidas --  */
    Route::post('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');

});

Route::apiResource('cardapio', 'CardapioController');
Route::apiResource('cardapio-produto', 'CardapioProdutoController');
Route::apiResource('categoria', 'CategoriaController');
Route::apiResource('cidade', 'CidadeController');
Route::apiResource('cupom', 'CupomController');
Route::apiResource('empresa', 'EmpresaController');
Route::apiResource('empresa-categoria', 'EmpresaCategoriaController');
Route::apiResource('empresa-cupom', 'EmpresaCupomController');
Route::apiResource('empresa-entrega', 'EmpresaEntregaController');
Route::apiResource('empresa-horario', 'EmpresaHorarioController');
Route::apiResource('empresa-recebimento', 'EmpresaRecebimentoController');
Route::apiResource('estado', 'EstadoController');
Route::apiResource('grupo', 'GrupoController');
Route::apiResource('log', 'LogController');
Route::apiResource('opcional', 'OpcionalController');
Route::apiResource('pedido', 'PedidoController');
Route::apiResource('pedido-produto', 'PedidoProdutoController');
Route::apiResource('permissao', 'PermissaoController');
Route::apiResource('produto', 'ProdutoController');
Route::apiResource('produto-detalhe', 'ProdutoDetalheController');
Route::apiResource('produto-opcional', 'ProdutoOpcionalController');
Route::apiResource('recebimento', 'RecebimentoController');
Route::apiResource('recebimento-cartao', 'RecebimentoCartaoController');
Route::apiResource('usuario', 'UserController');
Route::apiResource('usuario-cupom', 'UsuarioCupomController');
Route::apiResource('usuario-endereco', 'UsuarioEnderecoController');

/* -- Rotas não protegidas --  */
Route::post('login', 'AuthController@login');
Route::post('refresh', 'AuthController@refresh');
