<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioEndereco extends Model
{
    use HasFactory;
    protected $table = 'usuarios_enderecos';
    protected $fillable = ['user_id'];

    public function rules() {
        return [
            'user_id' => 'required',
        ];
    }
}
