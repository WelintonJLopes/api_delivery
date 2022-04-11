<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecebimentoCartao extends Model
{
    use HasFactory;
    protected $table = 'recebimentos_cartoes';
    protected $fillable = ['administradora', 'bandeira', 'recebimento_id'];

    public function rules()
    {
        return [
            'administradora' => 'required',
            'bandeira' => 'required',
            'recebimento_id' => 'required|exists:recebimentos,id',
        ];
    }
}
