<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cardapio extends Model
{
    use HasFactory;
    protected $fillable = ['cardapio', 'user_id', 'empresa_id'];

    public function rules() {
        return [
            'cardapio' => 'required|min:3|max:190',
            'user_id' => 'required|exists:users,id',
            'empresa_id' => 'required|exists:empresas,id'
        ];
    }
}
