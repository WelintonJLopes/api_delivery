<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaCupom extends Model
{
    use HasFactory;
    protected $table = 'empresas_cupons';
    protected $fillable = ['quantidade', 'empresa_id', 'cupom_id', 'user_id'];

    public function rules()
    {
        return [
            'quantidade' => 'required|integer',
            'empresa_id' => 'required|exists:empresas,id',
            'cupom_id' => 'required|exists:cupons,id',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function cupom()
    {
        return $this->belongsTo('App\Models\Cupom');
    }
}
