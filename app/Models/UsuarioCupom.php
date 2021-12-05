<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioCupom extends Model
{
    use HasFactory;
    protected $table = 'usuarios_cupons';
    protected $fillable = ['user_id', 'cupom_id'];
}
