<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;
    protected $fillable = ['estado', 'uf'];

    public function rules()
    {
        return [
            'estado' => 'required|unique:estados,estado,' . $this->id . '|min:3|max:50',
            'uf' => 'required|unique:estados,uf,' . $this->id . '|min:2|max:2',
        ];
    }
}
