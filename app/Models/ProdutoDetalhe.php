<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoDetalhe extends Model
{
    use HasFactory;
    protected $table = 'produtos_detalhes';
    protected $fillable = ['tamanho', 'pessoas', 'valor', 'desconto', 'principal', 'produto_id', 'user_id', 'empresa_id'];

    public function rules()
    {
        return [
            'tamanho' => 'min:3|max:50',
            'pessoas' => 'integer',
            'valor' => 'required|numeric',
            'desconto' => 'required|numeric',
            'principal' => 'required|boolean',
            'produto_id' => 'required|exists:produtos,id',
            'empresa_id' => 'required|exists:empresas,id'
        ];
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa');
    }
}
