<?php

namespace App\Http\Controllers;

use App\Models\ClientProfile;
use App\Models\User;
use Illuminate\Http\Request;

class ClientProfileController extends Controller
{
    /**
     * Retorna o Cliente pelo ID do user
     * in: id
     * out: client
     */
    public function get($id){
        $client = User::with('client')->findOrFail($id)->client()->with('user')->first();
        return response()->json([
            'success' => true,
            'client' => $client,
        ]);
    }

    /**
     * Retorna o Cliente logado
     * in: id
     * out: client
     */
    public function me(Request $request){
        $client = $request->user()->client()->with('user')->first();
        return response()->json([
            'success' => true,
            'client' => $client,
        ]);
    }

    /**
     * Cria um novo cliente
     * in: [user_id, trade_name, profile_picture_url, document_type, document]
     * out: client
     */
    public function create(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'trade_name' => 'required|string|max:255',
            'profile_picture_url' => 'nullable|string|max:255',
            'document_type' => 'nullable|string|max:255',
            'document' => 'nullable|string|max:255',
        ]);

        $client = ClientProfile::create($request->all());
        return response()->json([
            'success' => true,
            'client' => $client,
        ]);
    }

    /**
     * Atualiza o cliente logado
     * in: [user_id, trade_name, profile_picture_url, document_type, document]
     * out: client
     */
    public function update(Request $request){
        $validatedData = $request->validate([
            'trade_name' => 'required|string|max:255',
            'profile_picture_url' => 'nullable|string|max:255',
            'document_type' => 'nullable|string|max:255',
            'document' => 'nullable|string|max:255',
        ]);

        //Seleciona o client do usuário logado
        $client = $request->user()->client()->with('user')->first();
        $client->update($validatedData);

        return response()->json([
            'success' => true,
            'client' => $client,
        ]);
    }

    /**
     * Deleta o cliente
     * in: id
     * out: client
     */
    public function delete(Request $request){
        $client = $request->user()->client()->with('user')->first();
        $client->delete();

        return response()->json([
            'success' => true,
            'client' => $client,
        ]);
    }
}
