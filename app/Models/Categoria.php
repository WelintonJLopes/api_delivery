<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $fillable = ['categorias'];

    public function rules() {
        return [
            'categorias' => 'required|unique:categorias,categoria,'.$this->id.'|min:3|max:190',
        ];
    }
}
