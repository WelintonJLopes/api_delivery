<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaEntrega extends Model
{
    use HasFactory;
    protected $table = 'empresas_entregas';
    protected $fillable = ['taxa_entrega', 'empresa_id', 'cidade_id', 'estado_id', 'user_id'];

    public function rules()
    {
        return [
            'taxa_entrega' => 'required|numeric',
            'empresa_id' => 'required|exists:empresas,id',
            'cidade_id' => 'required|exists:cidades,id',
            'estado_id' => 'required|exists:estados,id',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function cidade()
    {
        return $this->belongsTo('App\Models\Cidade');
    }

    public function estado()
    {
        return $this->belongsTo('App\Models\Estado');
    }
}
