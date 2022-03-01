<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardapioProduto extends Model
{
    use HasFactory;
    protected $table = 'cardapios_produtos';
    protected $fillable = ['destaque', 'cardapio_id', 'produto_id', 'user_id', 'empresa_id'];

    public function rules()
    {
        return [
            'destaque' => 'required',
            'cardapio_id' => 'required|exists:cardapios,id',
            'produto_id' => 'required|exists:produtos,id',
            'user_id' => 'required|exists:users,id',
            'empresa_id' => 'required|exists:empresas,id'
        ];
    }

    public function produto()
    {
        return $this->belongsTo('App\Models\Produto');
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
