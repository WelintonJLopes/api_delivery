<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Repositories\GrupoRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class GrupoController extends Controller
{
    public function __construct(Grupo $grupo)
    {
        $this->grupo = $grupo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo grupo
        $grupoRepository = new GrupoRepository($this->grupo);

        // Recupera registro da tabela de relacionamentos
        $grupoRepository->selectAtributosRegistrosRelacionados(['permissoes']);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $grupoRepository->filtro($request->filtro);
        }

        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $grupoRepository->selectAtributos($request->atributos);
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $grupoRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $grupoRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $grupoRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $grupoRepository->limiteRegistros($request->limite);
        }

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $grupo = $grupoRepository->getResultadoPaginado($request->paginas);
        } else {
            $grupo = $grupoRepository->getResultado();
        }

        return response()->json($grupo, 200);
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
        $request->validate($this->grupo->rules());
        // Salva a request na tabela e retorna o registro inserido
        $grupo = $this->grupo->create($request->all());
        // Recupera modelo com relacionamentos
        $grupo = $this->grupo->with(['permissoes'])->find($grupo->id);
        // Retorna em formato JSON o registro inserido
        return response()->json($grupo, 201);
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
        $grupo = $this->grupo->with(['permissoes'])->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($grupo === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($grupo, 200);
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
        $grupo = $this->grupo->find($id);
        if ($grupo === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($grupo->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($grupo->rules());
        }

        // Preencher a instancia do medelo de grupo com a request encaminhada
        $grupo->fill($request->all());
        // Atualiza o updated_at
        $grupo->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $grupo->save();
        // Recupera modelo com relacionamentos
        $grupo = $this->grupo->with(['permissoes'])->find($grupo->id);

        return response()->json($grupo, 200);
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
        $grupo = $this->grupo->find($id);
        if ($grupo === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $grupo->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
