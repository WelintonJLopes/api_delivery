<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    protected $fillable = ['empresa', 'cnpj', 'imagem', 'sobre', 'telefone', 'email', 'rua', 'numero', 'bairro', 'complemento', 'cep', 'status', 'status_funcionamento', 'entrega', 'taxa_entrega', 'valor_minimo_pedido', 'user_id', 'cidade_id', 'estado_id', 'especialidade_id'];

    public function rules() {
        return [
            'empresa' => 'required|unique:empresas,empresa,'.$this->id.'|min:3|max:190',
            'cnpj' => 'integer|unique:empresas,cnpj,'.$this->id.'|min:10|max:15',
            'imagem' => 'file|mimes:png,jpeg,jpg',
            'sobre' => 'required',
            'telefone' => 'required|integer',
            'email' => 'email|unique:empresas,email,'.$this->id,
            'rua' => 'required',
            'numero' => 'required|integer',
            'bairro' => 'required',
            'complemento' => 'required',
            'cep' => 'required|integer',
            'status' => 'required|boolean',
            'status_funcionamento' => 'required|boolean',
            'entrega' => 'required|boolean',
            'taxa_entrega' => 'required',
            'valor_minimo_pedido' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'cidade_id' => 'required|exists:cidades,id',
            'estado_id' => 'required|exists:estados,id',
            'especialidade_id' => 'required|exists:especialidades,id',
        ];
    }
}
