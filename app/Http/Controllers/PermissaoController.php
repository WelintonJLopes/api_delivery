<?php

namespace App\Http\Controllers;

use App\Models\Permissao;
use App\Repositories\PermissaoRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class PermissaoController extends Controller
{
    public function __construct(Permissao $permissao)
    {
        $this->permissao = $permissao;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo permissao
        $permissaoRepository = new PermissaoRepository($this->permissao);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $permissaoRepository->filtro($request->filtro);
        }

        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $permissaoRepository->selectAtributos($request->atributos);
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $permissaoRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $permissaoRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $permissaoRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $permissaoRepository->limiteRegistros($request->limite);
        }

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $permissao = $permissaoRepository->getResultadoPaginado($request->paginas);
        } else {
            $permissao = $permissaoRepository->getResultado();
        }

        return response()->json($permissao, 200);
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
        $request->validate($this->permissao->rules());
        // Salva a request na tabela e retorna o registro inserido
        $permissao = $this->permissao->create($request->all());
        // Retorna em formato JSON o registro inserido
        return response()->json($permissao, 201);
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
        $permissao = $this->permissao->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($permissao === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($permissao, 200);
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
        $permissao = $this->permissao->find($id);
        if ($permissao === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($permissao->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($permissao->rules());
        }

        // Preencher a instancia do medelo de permissao com a request encaminhada
        $permissao->fill($request->all());
        // Atualiza o updated_at
        $permissao->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $permissao->save();

        return response()->json($permissao, 200);
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
        $permissao = $this->permissao->find($id);
        if ($permissao === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $permissao->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
