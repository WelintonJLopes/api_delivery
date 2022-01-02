<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class UserController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo user
        $userRepository = new UserRepository($this->user);
        
        // Recupera registro da tabela de relacionamentos
        $userRepository->selectAtributosRegistrosRelacionados(['grupo.permissoes', 'usuarios_enderecos.cidade', 'usuarios_enderecos.estado']);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $userRepository->filtro($request->filtro);         
        }
        
        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $userRepository->selectAtributos($request->atributos);         
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $userRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $userRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $userRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $userRepository->limiteRegistros($request->limite);
        }        

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $user = $userRepository->getResultadoPaginado($request->paginas);
        } else {
            $user = $userRepository->getResultado();
        }

        return response()->json($user, 200);
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
        $request->validate($this->user->rules());
        // Cryptografia da Senha
        $passCrypt = bcrypt($request->password);        
        // Salva a request na tabela e retorna o registro inserido
        $user = $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'password' => $passCrypt,
            'status' => $request->status,
            'grupo_id' => $request->grupo_id
        ]);

        // Recupera modelo com relacionamentos
        $user = $this->user->with(['grupo.permissoes', 'usuarios_enderecos.cidade', 'usuarios_enderecos.estado'])->find($user->id);

        // Retorna em formato JSON o registro inserido
        return response()->json($user, 201);
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
        $user = $this->user->with(['grupo.permissoes', 'usuarios_enderecos.cidade', 'usuarios_enderecos.estado'])->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($user === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($user, 200);
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
        $user = $this->user->find($id);
        if ($user === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($user->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($user->rules());
        }

        // Preencher a instancia do medelo de user com a request encaminhada
        $user->fill($request->all());

        if ($request->password) {
            // Cryptografia da Senha
            $passCrypt = bcrypt($request->password);
            $user->password = $passCrypt;
        }
        
        // Atualiza o updated_at
        $user->updated_at = date('Y-m-d H:i:s');
        
        // Salva a instancia do modelo atualizada pela request no banco
        $user->save();

        // Recupera modelo com relacionamentos
        $user = $this->user->with(['grupo.permissoes', 'usuarios_enderecos.cidade', 'usuarios_enderecos.estado'])->find($user->id);

        return response()->json($user, 200);
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
        $user = $this->user->find($id);        
        if ($user === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }
        // Deleta o registro selecionado
        $user->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
