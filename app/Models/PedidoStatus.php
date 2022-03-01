<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoStatus extends Model
{
    use HasFactory;
    protected $table = 'pedidos_status';
    protected $fillable = ['status'];
    public function rules() {
        return [
            'status' => 'required',
        ];
    }
}
