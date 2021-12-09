<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;
    protected $fillable = ['produto', 'imagem', 'descricao', 'valor'];
    public function rules() {
        return [
            'produto' => 'required|min:3|max:200',
        ];
    }
}
