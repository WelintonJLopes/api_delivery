<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'email', 'telefone', 'password', 'icone', 'data_nascimento', 'sexo', 'cpf', 'status', 'grupo_id', 'cidade_id'];
    
    public function rules() {
        return [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|min:3|max:190',
            'telefone' => 'required|min:9|max:12',
            'password' => 'required|min:4|max:255',
            'data_nascimento' => 'date',
            'sexo' => 'min:1|max:1',
            'cpf' => 'unique:users,cpf,'.$this->id,
            'status' => 'required|boolean',
            'grupo_id' => 'required|exists:grupos,id',
            'cidade_id' => 'required|exists:cidades,id',
        ];
    }

    public function grupo()
    {
        return $this->belongsTo('App\Models\Grupo');
    }

    public function cidade()
    {
        return $this->belongsTo('App\Models\Cidade');
    }

    public function usuarios_enderecos()
    {
        return $this->hasMany('App\Models\UsuarioEndereco');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
