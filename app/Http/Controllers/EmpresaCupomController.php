<?php

namespace App\Http\Controllers;

use App\Models\EmpresaCupom;
use App\Repositories\EmpresaCupomRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class EmpresaCupomController extends Controller
{
    public function __construct(EmpresaCupom $empresaCupom)
    {
        $this->empresaCupom = $empresaCupom; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo empresaCupom
        $empresaCupomRepository = new EmpresaCupomRepository($this->empresaCupom);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $empresaCupomRepository->filtro($request->filtro);         
        }
        
        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $empresaCupomRepository->selectAtributos($request->atributos);         
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $empresaCupomRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $empresaCupomRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $empresaCupomRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $empresaCupomRepository->limiteRegistros($request->limite);
        }        

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $empresaCupom = $empresaCupomRepository->getResultadoPaginado($request->paginas);
        } else {
            $empresaCupom = $empresaCupomRepository->getResultado();
        }

        return response()->json($empresaCupom, 200);
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
        $request->validate($this->empresaCupom->rules());        
        // Salva a request na tabela e retorna o registro inserido
        $empresaCupom = $this->empresaCupom->create($request->all());
        // Retorna em formato JSON o registro inserido
        return response()->json($empresaCupom, 201);
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
        $empresaCupom = $this->empresaCupom->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($empresaCupom === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($empresaCupom, 200);
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
        $empresaCupom = $this->empresaCupom->find($id);
        if ($empresaCupom === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($empresaCupom->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($empresaCupom->rules());
        }

        // Preencher a instancia do medelo de empresaCupom com a request encaminhada
        $empresaCupom->fill($request->all());
        // Atualiza o updated_at
        $empresaCupom->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $empresaCupom->save();

        return response()->json($empresaCupom, 200);
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
        $empresaCupom = $this->empresaCupom->find($id);        
        if ($empresaCupom === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $empresaCupom->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
