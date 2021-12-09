<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;
    protected $fillable = ['grupo'];

    public function rules() {
        return [
            'grupo' => 'required|unique:grupos,grupo,'.$this->id.'|min:3|max:100',
        ];
    }
}
