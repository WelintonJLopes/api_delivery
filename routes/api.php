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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('jwt.auth')->group(function () {
    /*     -- Rotas protegidas --  */
    Route::post('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');

    Route::apiResource('user', 'UserController');
    Route::apiResource('usuario-endereco', 'UsuarioEnderecoController');
    Route::apiResource('grupo', 'GrupoController');
    Route::apiResource('empresa', 'EmpresaController');
    Route::apiResource('especialidade', 'EspecialidadeController');
    Route::apiResource('cupom', 'CupomController');
    Route::apiResource('pedido', 'PedidoController');
    Route::apiResource('produto', 'ProdutoController');
    Route::apiResource('cidade', 'CidadeController');
    Route::apiResource('estado', 'EstadoController');
});

/* -- Rotas não protegidas --  */
Route::post('login', 'AuthController@login');
Route::post('refresh', 'AuthController@refresh');
