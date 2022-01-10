<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(Request $request)
    {
        $token = auth('api')->attempt(request(['email', 'password']));

        if ($token) {
            return $this->respondWithToken($token);
        } else {
            return response()->json(['error' => 'Não autorizado'], 401);
        }
    }

    public function me()
    {
        $userAuth = auth()->user(); 
        
        $user = $this->user->with(['grupo.permissoes', 'usuarios_enderecos.cidade', 'usuarios_enderecos.estado'])->find($userAuth->id);

        return response()->json($user);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
