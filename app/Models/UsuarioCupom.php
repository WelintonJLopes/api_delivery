<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioCupom extends Model
{
    use HasFactory;
    protected $table = 'usuarios_cupons';
    protected $fillable = ['utilizado', 'user_id', 'cupom_id'];

    public function rules()
    {
        return [
            'utilizado' => 'required|boolean',
            'user_id' => 'required|exists:users,id',
            'cupom_id' => 'required|exists:cupons,id',
        ];
    }

    public function cupom()
    {
        return $this->belongsTo('App\Models\Cupom');
    }
}
