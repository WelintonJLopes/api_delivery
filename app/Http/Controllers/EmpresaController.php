<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Repositories\EmpresaRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class EmpresaController extends Controller
{
    public function __construct(Empresa $empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo empresa
        $empresaRepository = new EmpresaRepository($this->empresa);

        // Recupera registro da tabela de relacionamentos
        $empresaRepository->selectAtributosRegistrosRelacionados(['cidade', 'estado', 'empresas_categorias.categoria', 'empresas_entregas.cidade', 'empresas_entregas.estado', 'empresas_horarios', 'empresas_recebimentos.recebimento']);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $empresaRepository->filtro($request->filtro);
        }

        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $empresaRepository->selectAtributos($request->atributos);
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $empresaRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $empresaRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $empresaRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $empresaRepository->limiteRegistros($request->limite);
        }

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $empresa = $empresaRepository->getResultadoPaginado($request->paginas);
        } else {
            $empresa = $empresaRepository->getResultado();
        }

        return response()->json($empresa, 200);
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
        $request->validate($this->empresa->rules());
        // Salva a request na tabela e retorna o registro inserido
        $empresa = $this->empresa->create($request->all());
        // Recupera modelo com relacionamentos
        $empresa = $this->empresa->with(['cidade', 'estado', 'empresas_categorias.categoria', 'empresas_entregas.cidade', 'empresas_entregas.estado', 'empresas_horarios', 'empresas_recebimentos.recebimento'])->find($empresa->id);
        // Retorna em formato JSON o registro inserido
        return response()->json($empresa, 201);
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
        $empresa = $this->empresa->with(['cidade', 'estado', 'empresas_categorias.categoria', 'empresas_entregas.cidade', 'empresas_entregas.estado', 'empresas_horarios', 'empresas_recebimentos.recebimento'])->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($empresa === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($empresa, 200);
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
        $empresa = $this->empresa->find($id);
        if ($empresa === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($empresa->user_id != auth()->user()->id) {
            return response()->json(['erro' => 'Usuário não tem permissão de alterar o recurso solicitado!'], 403);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($empresa->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($empresa->rules());
        }

        // Preencher a instancia do medelo de empresa com a request encaminhada
        $empresa->fill($request->all());
        // Atualiza o updated_at
        $empresa->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $empresa->save();
        // Recupera modelo com relacionamentos
        $empresa = $this->empresa->with(['cidade', 'estado', 'empresas_categorias.categoria', 'empresas_entregas.cidade', 'empresas_entregas.estado', 'empresas_horarios', 'empresas_recebimentos.recebimento'])->find($empresa->id);

        return response()->json($empresa, 200);
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
        $empresa = $this->empresa->find($id);
        if ($empresa === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }

        if ($empresa->user_id != auth()->user()->id) {
            return response()->json(['erro' => 'Usuário não tem permissão de deletar o recurso solicitado!'], 403);
        }

        // Deleta o registro selecionado
        $empresa->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
