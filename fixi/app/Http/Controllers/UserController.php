<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Get all users
     * In: api_token, filters{name,email,role}
     * Out: [users]
     */
    public function getAll(Request $request){
        $users = User::query()->with('client');

        if($request->has('filters')) {
            $filters = json_decode($request->filters);

            if($filters->name) {
                $users->where('name', 'like', '%' . $filters->name . '%');
            }

            if($filters->email) {
                $users->where('email', 'like', '%' . $filters->email . '%');
            }

            if($filters->role) {
                $users->where('role', 'like', '%' . $filters->role . '%');
            }
        }

        return response()->json([
            'success' => true,
            'users' => $users->get(['id', 'name', 'email', 'role']),
        ]);
    }

    /**
     * Get user
     * in: id
     * out: user
     */
    public function getById($id){
        $user = User::select(['id', 'name', 'email', 'role'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }

    public function create(Request $request){
        //Validação de dados
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $request->merge([
            'password' => Hash::make($request->password),
            'role' => 'student'
        ]);

        $user = User::create($request->all());

        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }

    public function update($id, Request $request){
        $user = User::findOrFail($id);
        //Verifica se o Token é do usuário logado
        if($request->user()->id != $user->id){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ]);
        }

        //Validação de dados
        $request->validate([
            'name' => 'nullable|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable',
        ]);

        if($request->has('name')){
            $user->update([
                'name' => $request->name
            ]);
        }

        if($request->has('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }

    public function delete($id){
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }

    /**
     * Busca de usuários
     * In: search (name/email)
     * Out: [users]
     */
    public function search(Request $request){
        $users = User::select(['id', 'name', 'email', 'role', 'provider', 'client'])
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }

    /**
     * Verifica se o token é valido
     * In: token
     * Out: user / null
     */
    public function me(Request $request){
        return response()->json([
            'success' => true,
            'is_valid' => $request->user() ? true : false,
            'user' => $request->user()??null,
        ]);
    }
}
