<?php

namespace App\Http\Controllers;

use App\Models\Cupom;
use App\Repositories\CupomRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class CupomController extends Controller
{
    public function __construct(Cupom $cupom)
    {
        $this->cupom = $cupom;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo cupom
        $cupomRepository = new CupomRepository($this->cupom);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $cupomRepository->filtro($request->filtro);         
        }

        // Verifica de a request tem o parametro atributos_usuario
        if ($request->has('atributos_usuario')) {
            $cupomRepository->selectAtributosRegistrosRelacionados('users:id,' . $request->atributos_usuario);
        } else {
            $cupomRepository->selectAtributosRegistrosRelacionados('users');
        }

        // Verifica de a request tem o parametro atributos_empresa
        if ($request->has('atributos_empresa')) {
            $cupomRepository->selectAtributosRegistrosRelacionados('empresas:id,' . $request->atributos_empresas);
        } else {
            $cupomRepository->selectAtributosRegistrosRelacionados('empresas');
        }
        
        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $cupomRepository->selectAtributos($request->atributos);         
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $cupomRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $cupomRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $cupomRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $cupomRepository->limiteRegistros($request->limite);
        }        

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $cupom = $cupomRepository->getResultadoPaginado($request->paginas);
        } else {
            $cupom = $cupomRepository->getResultado();
        }

        return response()->json($cupom, 200);
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
        $request->validate($this->cupom->rules());        
        // Salva a request na tabela e retorna o registro inserido
        $cupom = $this->cupom->create($request->all());
        // Insere o relacionamento de usuarios na tabela usuarios_cupons
        foreach ($request->usuarios as $user_id) {
            $cupom->users()->attach($user_id, ['utilizado' => false]);
        }
        // Insere o relacionamento de usuarios na tabela usuarios_cupons
        foreach ($request->empresas as $empresa_id) {
            $cupom->empresas()->attach($empresa_id, ['quantidade' => $request->quantidade]);
        }
        // Busca na tabela por id
        $cupom = $this->cupom->with('users')->with('empresas')->find($cupom->id);
        // Retorna em formato JSON o registro inserido
        return response()->json($cupom, 201);
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
        $cupom = $this->cupom->with('users')->with('empresas')->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($cupom === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($cupom, 200);
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
        $cupom = $this->cupom->find($id);
        if ($cupom === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($cupom->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($cupom->rules());
        }

        // Preencher a instancia do medelo de cupom com a request encaminhada
        $cupom->fill($request->all());
        // Atualiza o updated_at
        $cupom->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $cupom->save();

        return response()->json($cupom, 200);
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
        $cupom = $this->cupom->find($id);        
        if ($cupom === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Removendo os registros de relacionamento
        $cupom->users()->sync([]);
        $cupom->empresas()->sync([]);
        // Deleta o registro selecionado
        $cupom->delete();

        return response()->json(['msg' => 'O cupom foi removido com sucesso!'], 200);
    }
}
