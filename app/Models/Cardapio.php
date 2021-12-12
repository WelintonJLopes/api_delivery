<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cardapio extends Model
{
    use HasFactory;
    protected $fillable = ['cardapio'];

    public function rules() {
        return [
            'cardapio' => 'required|min:3|max:190',
        ];
    }
}
