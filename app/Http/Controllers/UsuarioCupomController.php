<?php

namespace App\Http\Controllers;

use App\Models\UsuarioCupom;
use App\Repositories\UsuarioCupomRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class UsuarioCupomController extends Controller
{
    public function __construct(UsuarioCupom $usuarioCupom)
    {
        $this->usuarioCupom = $usuarioCupom; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo usuarioCupom
        $usuarioCupomRepository = new UsuarioCupomRepository($this->usuarioCupom);

        // Recupera registro da tabela de relacionamentos
        $usuarioCupomRepository->selectAtributosRegistrosRelacionados(['cupom']);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $usuarioCupomRepository->filtro($request->filtro);         
        }
        
        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $usuarioCupomRepository->selectAtributos($request->atributos);         
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $usuarioCupomRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $usuarioCupomRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $usuarioCupomRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $usuarioCupomRepository->limiteRegistros($request->limite);
        }        

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $usuarioCupom = $usuarioCupomRepository->getResultadoPaginado($request->paginas);
        } else {
            $usuarioCupom = $usuarioCupomRepository->getResultado();
        }

        return response()->json($usuarioCupom, 200);
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
        $request->validate($this->usuarioCupom->rules());        
        // Salva a request na tabela e retorna o registro inserido
        $usuarioCupom = $this->usuarioCupom->create($request->all());
        // Recupera modelo com relacionamentos
        $usuarioCupom = $this->usuarioCupom->with(['cupom'])->find($usuarioCupom->id);
        // Retorna em formato JSON o registro inserido
        return response()->json($usuarioCupom, 201);
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
        $usuarioCupom = $this->usuarioCupom->with(['cupom'])->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($usuarioCupom === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($usuarioCupom, 200);
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
        $usuarioCupom = $this->usuarioCupom->find($id);
        if ($usuarioCupom === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($usuarioCupom->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($usuarioCupom->rules());
        }

        // Preencher a instancia do medelo de usuarioCupom com a request encaminhada
        $usuarioCupom->fill($request->all());
        // Atualiza o updated_at
        $usuarioCupom->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $usuarioCupom->save();
        // Recupera modelo com relacionamentos
        $usuarioCupom = $this->usuarioCupom->with(['cupom'])->find($usuarioCupom->id);

        return response()->json($usuarioCupom, 200);
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
        $usuarioCupom = $this->usuarioCupom->find($id);        
        if ($usuarioCupom === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $usuarioCupom->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
