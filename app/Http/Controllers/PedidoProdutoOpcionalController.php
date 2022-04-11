<?php

namespace App\Http\Controllers;

use App\Models\PedidoProdutoOpcional;
use App\Repositories\PedidoProdutoOpcionalRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class PedidoProdutoOpcionalController extends Controller
{
    public function __construct(PedidoProdutoOpcional $pedidoProdutoOpcional)
    {
        $this->pedidoProdutoOpcional = $pedidoProdutoOpcional;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo pedidoProdutoOpcional
        $pedidoProdutoOpcionalRepository = new PedidoProdutoOpcionalRepository($this->pedidoProdutoOpcional);

        // Recupera registro da tabela de relacionamentos
        $pedidoProdutoOpcionalRepository->selectAtributosRegistrosRelacionados(['opcional']);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $pedidoProdutoOpcionalRepository->filtro($request->filtro);
        }

        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $pedidoProdutoOpcionalRepository->selectAtributos($request->atributos);
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $pedidoProdutoOpcionalRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $pedidoProdutoOpcionalRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $pedidoProdutoOpcionalRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $pedidoProdutoOpcionalRepository->limiteRegistros($request->limite);
        }

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $pedidoProdutoOpcional = $pedidoProdutoOpcionalRepository->getResultadoPaginado($request->paginas);
        } else {
            $pedidoProdutoOpcional = $pedidoProdutoOpcionalRepository->getResultado();
        }

        return response()->json($pedidoProdutoOpcional, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Recebe a request e valida os campos
        $request->validate($this->pedidoProdutoOpcional->rules());
        // Salva a request na tabela e retorna o registro inserido
        $pedidoProdutoOpcional = $this->pedidoProdutoOpcional->create($request->all());
        // Recupera modelo com relacionamentos
        $pedidoProdutoOpcional = $this->pedidoProdutoOpcional->with(['opcional'])->find($pedidoProdutoOpcional->id);
        // Retorna em formato JSON o registro inserido
        return response()->json($pedidoProdutoOpcional, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Busca na tabela por id
        $pedidoProdutoOpcional = $this->pedidoProdutoOpcional->with(['opcional'])->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($pedidoProdutoOpcional === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($pedidoProdutoOpcional, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Verifica se o registro encaminhado pela request existe no banco
        $pedidoProdutoOpcional = $this->pedidoProdutoOpcional->find($id);
        if ($pedidoProdutoOpcional === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($pedidoProdutoOpcional->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($pedidoProdutoOpcional->rules());
        }

        // Preencher a instancia do medelo de pedidoProdutoOpcional com a request encaminhada
        $pedidoProdutoOpcional->fill($request->all());
        // Atualiza o updated_at
        $pedidoProdutoOpcional->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $pedidoProdutoOpcional->save();
        // Recupera modelo com relacionamentos
        $pedidoProdutoOpcional = $this->pedidoProdutoOpcional->with(['opcional'])->find($pedidoProdutoOpcional->id);

        return response()->json($pedidoProdutoOpcional, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Verifica se o registro encaminhado pela request existe no banco
        $pedidoProdutoOpcional = $this->pedidoProdutoOpcional->find($id);
        if ($pedidoProdutoOpcional === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $pedidoProdutoOpcional->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
