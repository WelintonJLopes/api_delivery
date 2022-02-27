<?php

namespace App\Http\Controllers;

use App\Models\Recebimento;
use App\Repositories\RecebimentoRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class RecebimentoController extends Controller
{
    public function __construct(Recebimento $recebimento)
    {
        $this->recebimento = $recebimento; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo recebimento
        $recebimentoRepository = new RecebimentoRepository($this->recebimento);

        // Recupera registro da tabela de relacionamentos
        $recebimentoRepository->selectAtributosRegistrosRelacionados(['recebimentos_cartoes']);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $recebimentoRepository->filtro($request->filtro);         
        }
        
        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $recebimentoRepository->selectAtributos($request->atributos);         
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $recebimentoRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $recebimentoRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $recebimentoRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $recebimentoRepository->limiteRegistros($request->limite);
        }        

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $recebimento = $recebimentoRepository->getResultadoPaginado($request->paginas);
        } else {
            $recebimento = $recebimentoRepository->getResultado();
        }

        return response()->json($recebimento, 200);
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
        $request->validate($this->recebimento->rules());        
        // Salva a request na tabela e retorna o registro inserido
        $recebimento = $this->recebimento->create($request->all());
        // Recupera modelo com relacionamentos
        $recebimento = $this->recebimento->with(['recebimentos_cartoes'])->find($recebimento->id);
        // Retorna em formato JSON o registro inserido
        return response()->json($recebimento, 201);
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
        $recebimento = $this->recebimento->with(['recebimentos_cartoes'])->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($recebimento === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($recebimento, 200);
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
        $recebimento = $this->recebimento->find($id);
        if ($recebimento === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($recebimento->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($recebimento->rules());
        }

        // Preencher a instancia do medelo de recebimento com a request encaminhada
        $recebimento->fill($request->all());
        // Atualiza o updated_at
        $recebimento->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $recebimento->save();
        // Recupera modelo com relacionamentos
        $recebimento = $this->recebimento->with(['recebimentos_cartoes'])->find($recebimento->id);

        return response()->json($recebimento, 200);
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
        $recebimento = $this->recebimento->find($id);        
        if ($recebimento === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $recebimento->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
