<?php

namespace App\Http\Controllers;

use App\Models\ProdutoDetalhe;
use App\Repositories\ProdutoDetalheRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class ProdutoDetalheController extends Controller
{
    public function __construct(ProdutoDetalhe $produtoDetalhe)
    {
        $this->produtoDetalhe = $produtoDetalhe; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo produtoDetalhe
        $produtoDetalheRepository = new ProdutoDetalheRepository($this->produtoDetalhe);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $produtoDetalheRepository->filtro($request->filtro);         
        }
        
        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $produtoDetalheRepository->selectAtributos($request->atributos);         
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $produtoDetalheRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $produtoDetalheRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $produtoDetalheRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $produtoDetalheRepository->limiteRegistros($request->limite);
        }        

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $produtoDetalhe = $produtoDetalheRepository->getResultadoPaginado($request->paginas);
        } else {
            $produtoDetalhe = $produtoDetalheRepository->getResultado();
        }

        return response()->json($produtoDetalhe, 200);
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
        $request->validate($this->produtoDetalhe->rules());        
        // Salva a request na tabela e retorna o registro inserido
        $produtoDetalhe = $this->produtoDetalhe->create($request->all());
        // Retorna em formato JSON o registro inserido
        return response()->json($produtoDetalhe, 201);
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
        $produtoDetalhe = $this->produtoDetalhe->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($produtoDetalhe === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($produtoDetalhe, 200);
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
        $produtoDetalhe = $this->produtoDetalhe->find($id);
        if ($produtoDetalhe === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($produtoDetalhe->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($produtoDetalhe->rules());
        }

        // Preencher a instancia do medelo de produtoDetalhe com a request encaminhada
        $produtoDetalhe->fill($request->all());
        // Atualiza o updated_at
        $produtoDetalhe->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $produtoDetalhe->save();

        return response()->json($produtoDetalhe, 200);
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
        $produtoDetalhe = $this->produtoDetalhe->find($id);        
        if ($produtoDetalhe === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $produtoDetalhe->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
