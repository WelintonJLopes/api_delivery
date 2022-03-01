<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $fillable = ['data_aceite', 'data_entrega', 'data_cancelamento', 'motivo_cancelamento', 'observacao', 'valor', 'troco', 'user_id', 'usuario_endereco_id', 'empresa_id', 'recebimento_id', 'recebimento_cartao_id', 'pedido_status_id', 'cupom_id'];
    
    public function rules() {
        return [
            'data_aceite' => 'date',
            'data_entrega' => 'date',
            'data_cancelamento' => 'date',
            'valor' => 'numeric',
            'troco' => 'numeric',
            'user_id' => 'required|exists:users,id',
            'usuario_endereco_id' => 'required|exists:usuarios_enderecos,id',
            'empresa_id' => 'required|exists:empresas,id',
            'recebimento_id' => 'required|exists:recebimentos,id',
            'recebimento_cartao_id' => 'exists:recebimentos_cartoes,id',
            'pedido_status_id' => 'required|exists:pedidos_status,id',
            'cupom_id' => 'exists:cupons,id',
        ];
    }

    public function pedidos_produtos()
    {
        return $this->hasMany('App\Models\PedidoProduto');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function usuario_endereco()
    {
        return $this->belongsTo('App\Models\UsuarioEndereco');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa');
    }

    public function recebimento()
    {
        return $this->belongsTo('App\Models\Recebimento');
    }

    public function recebimento_cartao()
    {
        return $this->belongsTo('App\Models\RecebimentoCartao');
    }

    public function pedido_status()
    {
        return $this->belongsTo('App\Models\PedidoStatus');
    }

    public function cupom()
    {
        return $this->belongsTo('App\Models\Cupom');
    }
}
