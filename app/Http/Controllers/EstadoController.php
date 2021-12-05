<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Repositories\EstadoRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class EstadoController extends Controller
{
    public function __construct(Estado $estado)
    {
        $this->estado = $estado;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo estado
        $estadoRepository = new EstadoRepository($this->estado);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $estadoRepository->filtro($request->filtro);         
        }
        
        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $estadoRepository->selectAtributos($request->atributos);         
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $estadoRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $estadoRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $estadoRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $estadoRepository->limiteRegistros($request->limite);
        }        

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $estado = $estadoRepository->getResultadoPaginado($request->paginas);
        } else {
            $estado = $estadoRepository->getResultado();
        }

        return response()->json($estado, 200);
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
        $request->validate($this->estado->rules());        
        // Salva a request na tabela e retorna o registro inserido
        $estado = $this->estado->create($request->all());
        // Retorna em formato JSON o registro inserido
        return response()->json($estado, 201);
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
        $estado = $this->estado->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($estado === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($estado, 200);
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
        $estado = $this->estado->find($id);
        if ($estado === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($estado->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($estado->rules());
        }

        // Preencher a instancia do medelo de estado com a request encaminhada
        $estado->fill($request->all());
        // Atualiza o updated_at
        $estado->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $estado->save();

        return response()->json($estado, 200);
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
        $estado = $this->estado->find($id);        
        if ($estado === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $estado->delete();

        return response()->json(['msg' => 'O estado foi removido com sucesso!'], 200);
    }
}
