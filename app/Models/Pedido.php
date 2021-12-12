<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $fillable = ['data_aceite', 'data_entrega', 'data_cancelamento', 'motivo_cancelamento', 'troco', 'user_id', 'empresa_id', 'cupom_id'];
    
    public function rules() {
        return [
            'data_aceite' => 'date',
            'data_entrega' => 'date',
            'data_cancelamento' => 'date',
            'troco' => 'numeric',
            'user_id' => 'required|exists:users,id',
            'empresa_id' => 'required|exists:empresas,id',
            'cupom_id' => 'exists:cupons,id',
        ];
    }
}
