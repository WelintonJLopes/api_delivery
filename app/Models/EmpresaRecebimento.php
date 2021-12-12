<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaRecebimento extends Model
{
    use HasFactory;
    protected $table = 'empresas_recebimentos';
    protected $fillable = ['empresa_id', 'recebimento_id'];

    public function rules() {
        return [
            'empresa_id' => 'required|exists:empresas,id',
            'recebimento_id' => 'required|exists:recebimentos,id',
        ];
    }
}
