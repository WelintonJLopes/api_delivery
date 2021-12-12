<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaEntrega extends Model
{
    use HasFactory;
    protected $table = 'empresas_entregas';
    protected $fillable = ['taxa_entrega', 'empresa_id', 'cidade_id'];

    public function rules() {
        return [
            'taxa_entrega' => 'required|numeric',
            'empresa_id' => 'required|exists:empresas,id',
            'cidade_id' => 'required|exists:cidades,id',
        ];
    }
}
