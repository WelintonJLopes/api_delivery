<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecebimentoCartao extends Model
{
    use HasFactory;
    protected $table = 'recebimentos_cartoes';
    protected $fillable = ['recebimento_id'];

    public function rules() {
        return [
            'recebimento_id' => 'required',
        ];
    }
}
