<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    protected $fillable = ['empresa', 'imagem', 'descricao', 'endereco', 'contato', 'user_id'];

    public function rules() {
        return [
            'empresa' => 'required|unique:empresas,empresa,'.$this->id.'|min:3|max:200',
        ];
    }
}
