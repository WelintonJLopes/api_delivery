<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cardapio extends Model
{
    use HasFactory;
    protected $fillable = ['cardapio', 'user_id', 'empresa_id'];

    public function rules()
    {
        return [
            'cardapio' => 'required|min:3|max:190',
            'empresa_id' => 'required|exists:empresas,id'
        ];
    }

    public function cardapios_produtos()
    {
        return $this->hasMany('App\Models\CardapioProduto');
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
