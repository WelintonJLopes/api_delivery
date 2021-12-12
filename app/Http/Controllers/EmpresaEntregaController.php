<?php

namespace App\Http\Controllers;

use App\Models\EmpresaEntrega;
use App\Repositories\EmpresaEntregaRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class EmpresaEntregaController extends Controller
{
    public function __construct(EmpresaEntrega $empresaEntrega)
    {
        $this->empresaEntrega = $empresaEntrega; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo empresaEntrega
        $empresaEntregaRepository = new EmpresaEntregaRepository($this->empresaEntrega);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $empresaEntregaRepository->filtro($request->filtro);         
        }
        
        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $empresaEntregaRepository->selectAtributos($request->atributos);         
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $empresaEntregaRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $empresaEntregaRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $empresaEntregaRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $empresaEntregaRepository->limiteRegistros($request->limite);
        }        

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $empresaEntrega = $empresaEntregaRepository->getResultadoPaginado($request->paginas);
        } else {
            $empresaEntrega = $empresaEntregaRepository->getResultado();
        }

        return response()->json($empresaEntrega, 200);
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
        $request->validate($this->empresaEntrega->rules());        
        // Salva a request na tabela e retorna o registro inserido
        $empresaEntrega = $this->empresaEntrega->create($request->all());
        // Retorna em formato JSON o registro inserido
        return response()->json($empresaEntrega, 201);
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
        $empresaEntrega = $this->empresaEntrega->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($empresaEntrega === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($empresaEntrega, 200);
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
        $empresaEntrega = $this->empresaEntrega->find($id);
        if ($empresaEntrega === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($empresaEntrega->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($empresaEntrega->rules());
        }

        // Preencher a instancia do medelo de empresaEntrega com a request encaminhada
        $empresaEntrega->fill($request->all());
        // Atualiza o updated_at
        $empresaEntrega->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $empresaEntrega->save();

        return response()->json($empresaEntrega, 200);
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
        $empresaEntrega = $this->empresaEntrega->find($id);        
        if ($empresaEntrega === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $empresaEntrega->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
