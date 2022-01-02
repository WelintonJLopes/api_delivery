<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recebimento extends Model
{
    use HasFactory;
    protected $table = 'recebimentos';
    protected $fillable = ['recebimento'];

    public function rules() {
        return [
            'recebimento' => 'required|unique:recebimentos,recebimento,'.$this->id.'|min:3|max:100',
        ];
    }

    public function recebimentos_cartoes()
    {
        return $this->hasMany('App\Models\RecebimentoCartao');
    }
}
