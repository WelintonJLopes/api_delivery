<?php

namespace App\Http\Controllers;

use App\Models\Opcional;
use App\Repositories\OpcionalRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class OpcionalController extends Controller
{
    public function __construct(Opcional $opcional)
    {
        $this->opcional = $opcional; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo opcional
        $opcionalRepository = new OpcionalRepository($this->opcional);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $opcionalRepository->filtro($request->filtro);         
        }
        
        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $opcionalRepository->selectAtributos($request->atributos);         
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $opcionalRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $opcionalRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $opcionalRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $opcionalRepository->limiteRegistros($request->limite);
        }        

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $opcional = $opcionalRepository->getResultadoPaginado($request->paginas);
        } else {
            $opcional = $opcionalRepository->getResultado();
        }

        return response()->json($opcional, 200);
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
        $request->validate($this->opcional->rules());        
        // Salva a request na tabela e retorna o registro inserido
        $opcional = $this->opcional->create($request->all());
        // Retorna em formato JSON o registro inserido
        return response()->json($opcional, 201);
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
        $opcional = $this->opcional->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($opcional === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($opcional, 200);
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
        $opcional = $this->opcional->find($id);
        if ($opcional === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($opcional->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($opcional->rules());
        }

        // Preencher a instancia do medelo de opcional com a request encaminhada
        $opcional->fill($request->all());
        // Atualiza o updated_at
        $opcional->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $opcional->save();

        return response()->json($opcional, 200);
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
        $opcional = $this->opcional->find($id);        
        if ($opcional === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $opcional->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
