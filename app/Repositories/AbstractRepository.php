<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtributosRegistrosRelacionados($atributos)
    {
        $this->model = $this->model->with($atributos);
    }

    public function selectRegistrosEmpresaId($id)
    {
        $this->model = $this->model->where('empresa_id', '=', $id);
    }

    public function selectRegistrosUserId($id)
    {
        $this->model = $this->model->where('user_id', '=', $id);
    }

    public function filtro($filtros)
    {
        $filtros = explode(';', $filtros); //$request->filtro

        foreach ($filtros as $key => $condicao) {
            $c = explode(':', $condicao);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
        }
    }

    public function selectRegistrosWhereNotNull($campo)
    {
        $this->model = $this->model->whereNotNull($campo);
    }

    public function selectRegistrosWhereNull($campo)
    {
        $this->model = $this->model->whereNull($campo);
    }

    public function selectAtributos($atributos)
    {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function orderByDescColumn($coluna)
    {
        $this->model = $this->model->orderByDesc($coluna);
    }

    public function orderByColumn($coluna)
    {
        $this->model = $this->model->orderBy($coluna);
    }

    public function limiteRegistros($numMaxRegistros)
    {
        $this->model = $this->model->limit($numMaxRegistros);
    }

    public function offsetRegistros($indiceRegistro)
    {
        $this->model = $this->model->offset($indiceRegistro);
    }

    public function getResultado()
    {
        return $this->model->get();
    }

    public function getResultadoPaginado($numRegistrosPorPagina)
    {
        return $this->model->paginate($numRegistrosPorPagina);
    }
}
