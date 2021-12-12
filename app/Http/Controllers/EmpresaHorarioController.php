<?php

namespace App\Http\Controllers;

use App\Models\EmpresaHorario;
use App\Repositories\EmpresaHorarioRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class EmpresaHorarioController extends Controller
{
    public function __construct(EmpresaHorario $empresaHorario)
    {
        $this->empresaHorario = $empresaHorario; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo empresaHorario
        $empresaHorarioRepository = new EmpresaHorarioRepository($this->empresaHorario);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $empresaHorarioRepository->filtro($request->filtro);         
        }
        
        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $empresaHorarioRepository->selectAtributos($request->atributos);         
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $empresaHorarioRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $empresaHorarioRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $empresaHorarioRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $empresaHorarioRepository->limiteRegistros($request->limite);
        }        

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $empresaHorario = $empresaHorarioRepository->getResultadoPaginado($request->paginas);
        } else {
            $empresaHorario = $empresaHorarioRepository->getResultado();
        }

        return response()->json($empresaHorario, 200);
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
        $request->validate($this->empresaHorario->rules());        
        // Salva a request na tabela e retorna o registro inserido
        $empresaHorario = $this->empresaHorario->create($request->all());
        // Retorna em formato JSON o registro inserido
        return response()->json($empresaHorario, 201);
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
        $empresaHorario = $this->empresaHorario->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($empresaHorario === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($empresaHorario, 200);
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
        $empresaHorario = $this->empresaHorario->find($id);
        if ($empresaHorario === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($empresaHorario->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($empresaHorario->rules());
        }

        // Preencher a instancia do medelo de empresaHorario com a request encaminhada
        $empresaHorario->fill($request->all());
        // Atualiza o updated_at
        $empresaHorario->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $empresaHorario->save();

        return response()->json($empresaHorario, 200);
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
        $empresaHorario = $this->empresaHorario->find($id);        
        if ($empresaHorario === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $empresaHorario->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
