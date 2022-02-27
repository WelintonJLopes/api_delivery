<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Repositories\PedidoRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class PedidoController extends Controller
{
    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo pedido
        $pedidoRepository = new PedidoRepository($this->pedido);

        // Recupera registro da tabela de relacionamentos
        $pedidoRepository->selectAtributosRegistrosRelacionados(['pedidos_produtos.produto', 'pedidos_produtos.produto_detalhe', 'pedidos_produtos.pedidos_produtos_opcionais.opcional']);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $pedidoRepository->filtro($request->filtro);         
        }
        
        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $pedidoRepository->selectAtributos($request->atributos);         
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $pedidoRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $pedidoRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $pedidoRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $pedidoRepository->limiteRegistros($request->limite);
        }        

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $pedido = $pedidoRepository->getResultadoPaginado($request->paginas);
        } else {
            $pedido = $pedidoRepository->getResultado();
        }

        return response()->json($pedido, 200);
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
        $request->validate($this->pedido->rules());        
        // Salva a request na tabela e retorna o registro inserido
        $pedido = $this->pedido->create($request->all());
        // Recupera modelo com relacionamentos
        $pedido = $this->pedido->with(['pedidos_produtos.produto', 'pedidos_produtos.produto_detalhe', 'pedidos_produtos.pedidos_produtos_opcionais.opcional'])->find($pedido->id);
        // Retorna em formato JSON o registro inserido
        return response()->json($pedido, 201);
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
        // Recupera modelo com relacionamentos
        $pedido = $this->pedido->with(['pedidos_produtos.produto', 'pedidos_produtos.produto_detalhe', 'pedidos_produtos.pedidos_produtos_opcionais.opcional'])->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($pedido === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($pedido, 200);
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
        $pedido = $this->pedido->find($id);
        if ($pedido === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($pedido->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($pedido->rules());
        }

        // Preencher a instancia do medelo de pedido com a request encaminhada
        $pedido->fill($request->all());
        // Atualiza o updated_at
        $pedido->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $pedido->save();
        // Recupera modelo com relacionamentos
        $pedido = $this->pedido->with(['pedidos_produtos.produto', 'pedidos_produtos.produto_detalhe', 'pedidos_produtos.pedidos_produtos_opcionais.opcional'])->find($pedido->id);

        return response()->json($pedido, 200);
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
        $pedido = $this->pedido->find($id);        
        if ($pedido === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $pedido->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
