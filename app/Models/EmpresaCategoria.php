<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaCategoria extends Model
{
    use HasFactory;
    protected $table = 'empresas_categorias';
    protected $fillable = ['empresa_id', 'categoria_id', 'user_id'];

    public function rules() {
        return [
            'empresa_id' => 'required|exists:empresas,id',
            'categoria_id' => 'required|exists:categorias,id',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function categoria()
    {
        return $this->belongsTo('App\Models\Categoria');
    }
}
