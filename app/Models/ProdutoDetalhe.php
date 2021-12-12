<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoDetalhe extends Model
{
    use HasFactory;
    protected $table = 'produtos_detalhes';
    protected $fillable = ['tamanho', 'pessoas', 'valor', 'desconto', 'principal', 'produto_id'];

    public function rules() {
        return [
            'tamanho' => 'min:3|max:50',
            'pessoas' => 'integer',
            'valor' => 'required|numeric',
            'desconto' => 'required|numeric',
            'principal' => 'required|boolean',
            'produto_id' => 'required|exists:produtos,id',
        ];
    }
}
