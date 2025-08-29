<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TutorController extends Controller
{
    /**
     * Display a listing of the resource.
     * Exibe uma lista de todos os tutores.
     */
    public function index()
    {
        // Retorna todos os tutores com os dados do usuário associado
        $tutors = Tutor::with('user')->paginate(10);
        return response()->json($tutors);
    }

    /**
     * Store a newly created resource in storage.
     * Cria um novo perfil de tutor.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|uuid|exists:users,id|unique:tutors,user_id',
            'education' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'bio' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tutor = Tutor::create($validator->validated());

        // O ideal seria também atualizar o 'role' do usuário para 'tutor'
        // $tutor->user->update(['role' => 'tutor']);

        return response()->json($tutor->load('user'), 201);
    }

    /**
     * Display the specified resource.
     * Exibe os detalhes de um tutor específico.
     */
    public function show(Tutor $tutor)
    {
        // Carrega os dados do usuário junto com o perfil do tutor
        return response()->json($tutor->load('user'));
    }

    /**
     * Update the specified resource in storage.
     * Atualiza os dados de um tutor.
     */
    public function update(Request $request, Tutor $tutor)
    {
        $validator = Validator::make($request->all(), [
            'education' => 'sometimes|required|string|max:255',
            'experience' => 'sometimes|required|string|max:255',
            'bio' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tutor->update($validator->validated());

        return response()->json($tutor->load('user'));
    }

    /**
     * Remove the specified resource from storage.
     * Remove um perfil de tutor.
     */
    public function destroy(Tutor $tutor)
    {
        $tutor->delete();

        // Retorna uma resposta vazia com status 204 (No Content)
        return response()->json(null, 204);
    }
}
