<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoProduto extends Model
{
    use HasFactory;
    protected $table = 'pedidos_produtos';
    protected $fillable = ['pedido_id', 'produto_id', 'valor', 'desconto', 'quantidade', 'observacao'];
    public function rules() {
        return [
            'observacao' => 'min:3|max:190',
        ];
    }
}
