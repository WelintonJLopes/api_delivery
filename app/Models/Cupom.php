<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    use HasFactory;
    protected $table = 'cupons';
    protected $fillable = ['cupom', 'imagem', 'descricao', 'valor', 'data_expiracao', 'status', 'user_id'];

    public function rules() {
        return [
            'cupom' => 'required|min:3|max:190',
            'imagem' => 'file|mimes:png,jpeg,jpg',
            'descricao' => 'required',
            'valor' => 'required|numeric',
            'data_expiracao' => 'required|date',
            'status' => 'required|boolean',
            'user_id' => 'required|exists:users,id'
        ];
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'usuarios_cupons')->withPivot('utilizado');
    }

    public function empresas()
    {
        return $this->belongsToMany('App\Models\Empresa', 'empresas_cupons')->withPivot('quantidade');
    }
}
