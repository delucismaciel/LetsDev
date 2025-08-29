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
        $users = User::query();

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
            'users' => $users->get(['id', 'name', 'email', 'role','profile_picture_url']),
        ]);
    }

    /**
     * Get user
     * in: id
     * out: user
     */
    public function getById($id){
        $user = User::select(['id', 'name', 'email', 'role','profile_picture_url'])->findOrFail($id);

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
            'profile_picture_url' => 'nullable|url'
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

    public function update(Request $request, $id){
        $user = User::findOrFail($id);

        //Validação de dados
        $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'profile_picture_url' => 'nullable|url'
        ]);

        $user->update($request->all());

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


}
