<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $fillable = ['log', 'user_id'];

    public function rules() {
        return [
            'log' => 'required',
            'user_id' => 'required|exists:users,id'
        ];
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
