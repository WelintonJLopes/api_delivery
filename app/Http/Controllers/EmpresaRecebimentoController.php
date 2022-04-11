<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\EmpresaRecebimento;
use App\Repositories\EmpresaRecebimentoRepository;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class EmpresaRecebimentoController extends Controller
{
    public function __construct(EmpresaRecebimento $empresaRecebimento)
    {
        $this->empresaRecebimento = $empresaRecebimento;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Instancia um objeto do tipo Repository passando o modelo empresaRecebimento
        $empresaRecebimentoRepository = new EmpresaRecebimentoRepository($this->empresaRecebimento);

        // Recupera registro da tabela de relacionamentos
        $empresaRecebimentoRepository->selectAtributosRegistrosRelacionados(['recebimento.recebimentos_cartoes']);

        // Verifica se a resquest tem o parametro filtro
        if ($request->has('filtro')) {
            $empresaRecebimentoRepository->filtro($request->filtro);
        }

        // Verifica se a resquest tem o parametro atributos
        if ($request->has('atributos')) {
            $empresaRecebimentoRepository->selectAtributos($request->atributos);
        }

        // Verifica se a resquest tem o parametro order
        if ($request->has('order')) {
            $empresaRecebimentoRepository->orderByColumn($request->order);
        }

        // Verifica se a resquest tem o parametro order_desc
        if ($request->has('order_desc')) {
            $empresaRecebimentoRepository->orderByDescColumn($request->order_desc);
        }

        // Verifica se a resquest tem o parametro offset
        if ($request->has('offset')) {
            $empresaRecebimentoRepository->offsetRegistros($request->offset);
        }

        // Verifica se a resquest tem o parametro limite
        if ($request->has('limite')) {
            $empresaRecebimentoRepository->limiteRegistros($request->limite);
        }

        // Verifica se a resquest tem o parametro paginas
        if ($request->has('paginas')) {
            $empresaRecebimento = $empresaRecebimentoRepository->getResultadoPaginado($request->paginas);
        } else {
            $empresaRecebimento = $empresaRecebimentoRepository->getResultado();
        }

        return response()->json($empresaRecebimento, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empresa = Empresa::find($request->empresa_id);
        if ($empresa === null) {
            return response()->json(['erro' => 'Usuário não tem permissão de inserir o recurso solicitado!'], 403);
        }

        if ($empresa->user_id != auth()->user()->id) {
            return response()->json(['erro' => 'Usuário não tem permissão de inserir o recurso solicitado!'], 403);
        }

        // Recebe a request e valida os campos
        $request->validate($this->empresaRecebimento->rules());
        // Salva a request na tabela e retorna o registro inserido
        $empresaRecebimento = $this->empresaRecebimento->create($request->all());
        // Recupera modelo com relacionamentos
        $empresaRecebimento = $this->empresaRecebimento->with(['recebimento.recebimentos_cartoes'])->find($empresaRecebimento->id);
        // Retorna em formato JSON o registro inserido
        return response()->json($empresaRecebimento, 201);
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
        $empresaRecebimento = $this->empresaRecebimento->with(['recebimento.recebimentos_cartoes'])->find($id);
        // Verifica se a busca retornou algum registro, caso não retorne devolve msg de erro
        if ($empresaRecebimento === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404);
        }
        // Resposta com registro buscado no banco
        return response()->json($empresaRecebimento, 200);
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
        $empresaRecebimento = $this->empresaRecebimento->find($id);
        if ($empresaRecebimento === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe!'], 404);
        }

        if ($empresaRecebimento->user_id != auth()->user()->id) {
            return response()->json(['erro' => 'Usuário não tem permissão de alterar o recurso solicitado!'], 403);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];
            // Percorre todas as regras definidas no model
            foreach ($empresaRecebimento->rules() as $input => $regra) {
                // Coletar apenas as regras aplicaveis aos parametros da requição parcial
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($empresaRecebimento->rules());
        }

        // Preencher a instancia do medelo de empresaRecebimento com a request encaminhada
        $empresaRecebimento->fill($request->all());
        // Atualiza o updated_at
        $empresaRecebimento->updated_at = date('Y-m-d H:i:s');
        // Salva a instancia do modelo atualizada pela request no banco
        $empresaRecebimento->save();
        // Recupera modelo com relacionamentos
        $empresaRecebimento = $this->empresaRecebimento->with(['recebimento.recebimentos_cartoes'])->find($empresaRecebimento->id);

        return response()->json($empresaRecebimento, 200);
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
        $empresaRecebimento = $this->empresaRecebimento->find($id);
        if ($empresaRecebimento === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe!'], 404);
        }

        if ($empresaRecebimento->user_id != auth()->user()->id) {
            return response()->json(['erro' => 'Usuário não tem permissão de deletar o recurso solicitado!'], 403);
        }

        // Deleta o registro selecionado
        $empresaRecebimento->delete();

        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
