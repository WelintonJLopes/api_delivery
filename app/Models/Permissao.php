<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{
    use HasFactory;
    protected $table = 'permissoes';
    protected $fillable = ['permissao', 'descricao', 'grupo_id'];
    public function rules() {
        return [
            'permissao' => 'required|unique:permissoes,permissao,'.$this->id.'|min:3|max:190',
        ];
    }
}
