<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoProduto extends Model
{
    use HasFactory;
    protected $table = 'pedidos_produtos';
    protected $fillable = ['valor', 'desconto', 'quantidade', 'observacao', 'pedido_id', 'produto_id'];
    public function rules() {
        return [
            'valor' => 'required|numeric',
            'desconto' => 'required|numeric',
            'quantidade' => 'required|integer',
            'observacao' => 'min:3|max:190',
            'pedido_id' => 'required|exists:pedidos,id',
            'produto_id' => 'required|exists:produtos,id'
        ];
    }
}
