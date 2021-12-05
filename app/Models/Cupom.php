<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    use HasFactory;
    protected $table = 'cupons';
    protected $fillable = ['cupom', 'imagem', 'descricao', 'valor', 'data_expiracao'];

    public function rules() {
        return [
            'cupom' => 'required|min:3|max:200',
        ];
    }
}
