<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoOpcional extends Model
{
    use HasFactory;
    protected $table = 'produtos_opcionais';
    protected $fillable = ['produto_id'];

    public function rules() {
        return [
            'produto_id' => 'required',
        ];
    }
}
