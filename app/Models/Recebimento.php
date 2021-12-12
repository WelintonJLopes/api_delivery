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
            'recebimento' => 'required',
        ];
    }
}
