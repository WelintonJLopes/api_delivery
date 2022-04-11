<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioEndereco extends Model
{
    use HasFactory;
    protected $table = 'usuarios_enderecos';
    protected $fillable = ['apelido', 'rua', 'numero', 'bairro', 'complemento', 'cep', 'principal', 'user_id', 'cidade_id', 'estado_id'];

    public function rules()
    {
        return [
            'apelido' => 'required',
            'rua' => 'required',
            'numero' => 'required|integer',
            'bairro' => 'required',
            'complemento' => 'required',
            'cep' => 'required|integer',
            'principal' => 'required|boolean',
            'user_id' => 'required|exists:users,id',
            'cidade_id' => 'required|exists:cidades,id',
            'estado_id' => 'required|exists:estados,id',
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
