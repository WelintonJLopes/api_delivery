<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;
    protected $fillable = ['produto', 'descricao', 'destaque', 'imagem', 'status', 'user_id', 'empresa_id', 'categoria_id'];
    public function rules()
    {
        return [
            'produto' => 'required|min:3|max:190',
            'descricao' => 'required|min:3|max:190',
            /* 'imagem' => 'file|mimes:png,jpeg,jpg', */
            'status' => 'required|boolean',
            'empresa_id' => 'required|exists:empresas,id',
            'categoria_id' => 'required|exists:categorias,id',
        ];
    }

    public function cardapios_produtos()
    {
        return $this->hasMany('App\Models\CardapioProduto');
    }

    public function produtos_detalhes()
    {
        return $this->hasMany('App\Models\ProdutoDetalhe');
    }

    public function produtos_opcionais()
    {
        return $this->hasMany('App\Models\ProdutoOpcional');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa');
    }

    public function categoria()
    {
        return $this->belongsTo('App\Models\Categoria');
    }
}
