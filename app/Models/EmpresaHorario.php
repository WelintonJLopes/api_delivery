<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaHorario extends Model
{
    use HasFactory;
    protected $table = 'empresas_horarios';
    protected $fillable = ['empresa_id'];

    public function rules() {
        return [
            'empresa_id' => 'required',
        ];
    }
}
