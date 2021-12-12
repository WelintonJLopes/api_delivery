<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaHorario extends Model
{
    use HasFactory;
    protected $table = 'empresas_horarios';
    protected $fillable = ['dia', 'abertura', 'fechamento', 'intervalo', 'volta_intervalo', 'empresa_id'];

    public function rules() {
        return [
            'dia' => 'required',
            'abertura' => 'required',
            'fechamento' => 'required',
            'empresa_id' => 'required|exists:empresas,id',
        ];
    }
}
