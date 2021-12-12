<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;
    protected $fillable = ['produto', 'descricao', 'imagem', 'status', 'user_id', 'empresa_id', 'categoria_id'];
    public function rules() {
        return [
            'produto' => 'required|min:3|max:190',
            'descricao' => 'required|min:3|max:190',
            'imagem' => 'file|mimes:png,jpeg,jpg',
            'status' => 'required|boolean',
            'user_id' => 'required|exists:users,id',
            'empresa_id' => 'required|exists:empresas,id',
            'categoria_id' => 'required|exists:categorias,id',
        ];
    }
}
