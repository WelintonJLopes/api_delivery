<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Repositories\CidadeRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class CidadeController extends Controller
{
    public function __construct(Cidade $cidade)
    {
        $this->cidade = $cidade;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo cidade
        $cidadeRepository = new CidadeRepository($this->cidade);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $cidadeRepository->filtro($request->filtro);
        }

        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $cidadeRepository->selectAtributos($request->atributos);
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $cidadeRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $cidadeRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $cidadeRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $cidadeRepository->limiteRegistros($request->limite);
        }

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $cidade = $cidadeRepository->getResultadoPaginado($request->paginas);
        } else {
            $cidade = $cidadeRepository->getResultado();
        }

        return response()->json($cidade, 200);
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
        $request->validate($this->cidade->rules());
        // Salva a request na tabela e retorna o registro inserido
        $cidade = $this->cidade->create($request->all());
        // Retorna em formato JSON o registro inserido
        return response()->json($cidade, 201);
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
        $cidade = $this->cidade->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($cidade === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($cidade, 200);
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
        $cidade = $this->cidade->find($id);
        if ($cidade === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($cidade->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($cidade->rules());
        }

        // Preencher a instancia do medelo de cidade com a request encaminhada
        $cidade->fill($request->all());
        // Atualiza o updated_at
        $cidade->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $cidade->save();

        return response()->json($cidade, 200);
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
        $cidade = $this->cidade->find($id);
        if ($cidade === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $cidade->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
