<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaCupom extends Model
{
    use HasFactory;
    protected $table = 'empresas_cupons';
    protected $fillable = ['empresa_id', 'cupom_id', 'quantidade'];
}
