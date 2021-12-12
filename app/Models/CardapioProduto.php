<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardapioProduto extends Model
{
    use HasFactory;
    protected $table = 'cardapios_produtos';
    protected $fillable = ['cardapio_id', 'produto_id'];

    public function rules() {
        return [
            'cardapio_id' => 'required',
        ];
    }
}
