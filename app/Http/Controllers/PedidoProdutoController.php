<?php

namespace App\Http\Controllers;

use App\Models\PedidoProduto;
use App\Repositories\PedidoProdutoRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class PedidoProdutoController extends Controller
{
    public function __construct(PedidoProduto $pedidoProduto)
    {
        $this->pedidoProduto = $pedidoProduto;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo pedidoProduto
        $pedidoProdutoRepository = new PedidoProdutoRepository($this->pedidoProduto);

        // Recupera registro da tabela de relacionamentos
        $pedidoProdutoRepository->selectAtributosRegistrosRelacionados(['produto', 'produto_detalhe', 'pedidos_produtos_opcionais.opcional']);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $pedidoProdutoRepository->filtro($request->filtro);
        }

        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $pedidoProdutoRepository->selectAtributos($request->atributos);
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $pedidoProdutoRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $pedidoProdutoRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $pedidoProdutoRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $pedidoProdutoRepository->limiteRegistros($request->limite);
        }

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $pedidoProduto = $pedidoProdutoRepository->getResultadoPaginado($request->paginas);
        } else {
            $pedidoProduto = $pedidoProdutoRepository->getResultado();
        }

        return response()->json($pedidoProduto, 200);
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
        $request->validate($this->pedidoProduto->rules());
        // Salva a request na tabela e retorna o registro inserido
        $pedidoProduto = $this->pedidoProduto->create($request->all());
        // Recupera modelo com relacionamentos
        $pedidoProduto = $this->pedidoProduto->with(['produto', 'produto_detalhe', 'pedidos_produtos_opcionais.opcional'])->find($pedidoProduto->id);
        // Retorna em formato JSON o registro inserido
        return response()->json($pedidoProduto, 201);
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
        $pedidoProduto = $this->pedidoProduto->with(['produto', 'produto_detalhe', 'pedidos_produtos_opcionais.opcional'])->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($pedidoProduto === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($pedidoProduto, 200);
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
        $pedidoProduto = $this->pedidoProduto->find($id);
        if ($pedidoProduto === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($pedidoProduto->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($pedidoProduto->rules());
        }

        // Preencher a instancia do medelo de pedidoProduto com a request encaminhada
        $pedidoProduto->fill($request->all());
        // Atualiza o updated_at
        $pedidoProduto->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $pedidoProduto->save();
        // Recupera modelo com relacionamentos
        $pedidoProduto = $this->pedidoProduto->with(['produto', 'produto_detalhe', 'pedidos_produtos_opcionais.opcional'])->find($pedidoProduto->id);

        return response()->json($pedidoProduto, 200);
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
        $pedidoProduto = $this->pedidoProduto->find($id);
        if ($pedidoProduto === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $pedidoProduto->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
