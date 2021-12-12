<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    use HasFactory;
    protected $fillable = ['cidade', 'cep'];

    public function rules() {
        return [
            'cidade' => 'required|unique:cidades,cidade,'.$this->id.'|min:3|max:100',
            'cep' => 'required|min:8|max:8'
        ];
    }
}
