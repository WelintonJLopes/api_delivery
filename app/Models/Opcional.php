<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opcional extends Model
{
    use HasFactory;
    protected $table = 'opcionais';
    protected $fillable = ['opcional', 'descricao', 'valor', 'status', 'minimo', 'maximo', 'user_id', 'empresa_id'];

    public function rules() {
        return [
            'opcional' => 'required',
            'valor' => 'required|numeric',
            'status' => 'required|boolean',
            'minimo' => 'required|numeric',
            'maximo' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'empresa_id' => 'required|exists:empresas,id',
        ];
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
