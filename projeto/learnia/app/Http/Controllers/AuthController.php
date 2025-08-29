<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //Loga o user na API usando Sanctum
        $attemptAuth = Auth::attempt(['email' => $request->email, 'password' => $request->password]);

        // 3. Se a autenticação for bem-sucedida, gere o token 🔑
        $user = User::where('email', $request->email)->firstOrFail();

        // Revoga todos os tokens antigos para garantir que apenas um esteja ativo
        $user->tokens()->delete();

        // Cria o novo token
        $token = $user->createToken('auth_token')->plainTextToken;
        if (!$attemptAuth) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user' => Auth::user(),
        ]);
    }

    //Em construção
    public function logout(Request $request){
        //Desloga o user da api
        $user = $request->user();

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
