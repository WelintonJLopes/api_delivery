<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Produto;
use App\Repositories\ProdutoRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class ProdutoController extends Controller
{
    public function __construct(Produto $produto)
    {
        $this->produto = $produto;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo produto
        $produtoRepository = new ProdutoRepository($this->produto);

        // Recupera registro da tabela de relacionamentos
        $produtoRepository->selectAtributosRegistrosRelacionados(['produtos_detalhes', 'produtos_opcionais.opcional', 'categoria', 'empresa']);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $produtoRepository->filtro($request->filtro);
        }

        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $produtoRepository->selectAtributos($request->atributos);
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $produtoRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $produtoRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $produtoRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $produtoRepository->limiteRegistros($request->limite);
        }

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $produto = $produtoRepository->getResultadoPaginado($request->paginas);
        } else {
            $produto = $produtoRepository->getResultado();
        }

        return response()->json($produto, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empresa = Empresa::find($request->empresa_id);
        if ($empresa === null) {
            return response()->json(['erro' => 'Usuário não tem permissão de inserir o recurso solicitado!'], 403);
        }

        if ($empresa->user_id != auth()->user()->id) {
            return response()->json(['erro' => 'Usuário não tem permissão de inserir o recurso solicitado!'], 403);
        }

        // Recebe a request e valida os campos
        $request->validate($this->produto->rules());
        // Salva a request na tabela e retorna o registro inserido
        $produto = $this->produto->create([
            'produto' => $request->produto,
            'descricao' => $request->descricao,
            'categoria_id' => $request->categoria_id,
            'status' => $request->status,
            'imagem' => $request->imagem,
            'destaque' => $request->destaque,
            'empresa_id' => $request->empresa_id,
            'user_id' => auth()->user()->id,
        ]);

        // Insere o relacionamento na tabela cardapios_produtos
        $produto->cardapios_produtos()->create([
            'user_id' => auth()->user()->id,
            'empresa_id' => $request->empresa_id,
            'cardapio_id' => $request->cardapio_id,
            'destaque' => 0
        ]);

        // Insere o relacionamento na tabela produtos_opcionais
        if ($request->produtos_opcionais) {
            foreach ($request->produtos_opcionais as $opcional_id) {
                $produto->produtos_opcionais()->create([
                    'user_id' => auth()->user()->id,
                    'empresa_id' => $request->empresa_id,
                    'opcional_id' => $opcional_id
                ]);
            }
        }

        // Recupera modelo com relacionamentos
        $produto = $this->produto->with(['produtos_detalhes', 'produtos_opcionais.opcional', 'categoria', 'empresa'])->find($produto->id);
        // Retorna em formato JSON o registro inserido
        return response()->json($produto, 201);
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
        $produto = $this->produto->with(['produtos_detalhes', 'produtos_opcionais.opcional', 'categoria', 'empresa'])->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($produto === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($produto, 200);
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
        $produto = $this->produto->find($id);
        if ($produto === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($produto->user_id != auth()->user()->id) {
            return response()->json(['erro' => 'Usuário não tem permissão de alterar o recurso solicitado!'], 403);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($produto->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($produto->rules());
        }

        // Preencher a instancia do medelo de produto com a request encaminhada
        $produto->fill($request->all());
        // Atualiza o updated_at
        $produto->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $produto->save();

        // Verifica se a request contem um array de produtos_opcionais 
        if ($request->produtos_opcionais) {
            // Remove os registros de relacionamento
            $produto->produtos_opcionais()->delete();
            // Insere o relacionamento na tabela produtos_opcionais
            foreach ($request->produtos_opcionais as $opcional_id) {
                $produto->produtos_opcionais()->create([
                    'user_id' => auth()->user()->id,
                    'empresa_id' => $request->empresa_id,
                    'opcional_id' => $opcional_id
                ]);
            }
        }

        // Recupera modelo com relacionamentos
        $produto = $this->produto->with(['produtos_detalhes', 'produtos_opcionais.opcional', 'categoria', 'empresa'])->find($produto->id);

        return response()->json($produto, 200);
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
        $produto = $this->produto->find($id);
        if ($produto === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }

        if ($produto->user_id != auth()->user()->id) {
            return response()->json(['erro' => 'Usuário não tem permissão de deletar o recurso solicitado!'], 403);
        }

        $produto->produtos_opcionais()->delete();
        $produto->cardapios_produtos()->delete();

        // Deleta o registro selecionado
        $produto->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
