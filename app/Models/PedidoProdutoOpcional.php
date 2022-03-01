<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoProdutoOpcional extends Model
{
    use HasFactory;
    protected $table = 'pedidos_produtos_opcionais';
    protected $fillable = ['quantidade', 'valor', 'pedido_id', 'produto_id', 'opcional_id', 'user_id', 'empresa_id'];
    public function rules()
    {
        return [
            'quantidade' => 'required|integer',
            'valor' => 'required|numeric',
            'pedido_id' => 'required|exists:pedidos,id',
            'produto_id' => 'required|exists:produtos,id',
            'opcional_id' => 'required|exists:opcionais,id',
            'user_id' => 'required|exists:users,id',
            'empresa_id' => 'required|exists:empresas,id'
        ];
    }

    public function opcional()
    {
        return $this->belongsTo('App\Models\Opcional');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa');
    }
}
