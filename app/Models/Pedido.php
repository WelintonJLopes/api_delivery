<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $fillable = ['status', 'data_entrega', 'data_pagamento', 'data_cancelamento', 'user_id', 'empresa_id', 'cupom_id'];
}
