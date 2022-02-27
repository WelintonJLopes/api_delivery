<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoProduto extends Model
{
    use HasFactory;
    protected $table = 'pedidos_produtos';
    protected $fillable = ['valor', 'desconto', 'quantidade', 'observacao', 'pedido_id', 'produto_id', 'produto_detalhe_id'];
    public function rules() {
        return [
            'valor' => 'required|numeric',
            'desconto' => 'required|numeric',
            'quantidade' => 'required|integer',
            'observacao' => 'min:3|max:190',
            'pedido_id' => 'required|exists:pedidos,id',
            'produto_id' => 'required|exists:produtos,id',
            'produto_detalhe_id' => 'required|exists:produtos_detalhes,id',
        ];
    }

    public function produto()
    {
        return $this->belongsTo('App\Models\Produto');
    }

    public function produto_detalhe()
    {
        return $this->belongsTo('App\Models\ProdutoDetalhe');
    }

    public function pedidos_produtos_opcionais()
    {
        return $this->hasMany('App\Models\PedidoProdutoOpcional');
    }
}
