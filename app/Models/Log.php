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
            'log' => 'required|min:3|max:200',
        ];
    }
}
