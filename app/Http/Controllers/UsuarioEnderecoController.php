<?php

namespace App\Http\Controllers;

use App\Models\UsuarioEndereco;
use App\Repositories\UsuarioEnderecoRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class UsuarioEnderecoController extends Controller
{
    public function __construct(UsuarioEndereco $usuarioEndereco)
    {
        $this->usuarioEndereco = $usuarioEndereco; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo usuarioEndereco
        $usuarioEnderecoRepository = new UsuarioEnderecoRepository($this->usuarioEndereco);

        // Recupera registro da tabela de relacionamentos
        $usuarioEnderecoRepository->selectAtributosRegistrosRelacionados(['cidade', 'estado']);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $usuarioEnderecoRepository->filtro($request->filtro);         
        }
        
        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $usuarioEnderecoRepository->selectAtributos($request->atributos);         
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $usuarioEnderecoRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $usuarioEnderecoRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $usuarioEnderecoRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $usuarioEnderecoRepository->limiteRegistros($request->limite);
        }        

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $usuarioEndereco = $usuarioEnderecoRepository->getResultadoPaginado($request->paginas);
        } else {
            $usuarioEndereco = $usuarioEnderecoRepository->getResultado();
        }

        return response()->json($usuarioEndereco, 200);
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
        $request->validate($this->usuarioEndereco->rules());        
        // Salva a request na tabela e retorna o registro inserido
        $usuarioEndereco = $this->usuarioEndereco->create($request->all());
        // Recupera modelo com relacionamentos
        $usuarioEndereco = $this->usuarioEndereco->with(['cidade', 'estado'])->find($usuarioEndereco->id);
        // Retorna em formato JSON o registro inserido
        return response()->json($usuarioEndereco, 201);
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
        $usuarioEndereco = $this->usuarioEndereco->with(['cidade', 'estado'])->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($usuarioEndereco === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($usuarioEndereco, 200);
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
        $usuarioEndereco = $this->usuarioEndereco->find($id);
        if ($usuarioEndereco === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($usuarioEndereco->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($usuarioEndereco->rules());
        }

        // Preencher a instancia do medelo de usuarioEndereco com a request encaminhada
        $usuarioEndereco->fill($request->all());
        // Atualiza o updated_at
        $usuarioEndereco->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $usuarioEndereco->save();
        // Recupera modelo com relacionamentos
        $usuarioEndereco = $this->usuarioEndereco->with(['cidade', 'estado'])->find($usuarioEndereco->id);

        return response()->json($usuarioEndereco, 200);
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
        $usuarioEndereco = $this->usuarioEndereco->find($id);        
        if ($usuarioEndereco === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $usuarioEndereco->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
