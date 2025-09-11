<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\ProviderProfile;
use App\Models\User;
use Illuminate\Http\Request;

class ProviderProfileController extends Controller
{
    /** Retorna o Provider logado
     * in:
     * out: provider
     */
    public function me(Request $request){
        $provider = $request->user()->provider()->with('user')->first();
        return response()->json([
            'success' => true,
            'provider' => $provider,
        ]);
    }

    /**
     * Retorna o Provider pelo id
     * in: id
     * out: provider
     */
    public function get($id){
        $provider = User::findOrFail($id)->with('provider','offeredServices','mainAddress')->first();

        return response()->json([
            'success' => true,
            'provider' => $provider,
        ]);
    }

    /**
     * Busca o provider por busca
     * in: search, order(recent, name, rating), service, page, limit
     * out: [providers]
     */
    public function search(Request $request){
         $providersQuery = User::query()
            ->where('role', Role::PROVIDER)
            ->with(['provider', 'offeredServices', 'mainAddress']); // Carrega as relações do User

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $providersQuery->where(function ($query) use ($searchTerm) {
                // Busca diretamente no nome e email do usuário
                $query->where(function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm) // Corrigido 'searchTerm para a variável correta
                    // OU busca na bio (tabela provider_profiles, através da relação)
                    ->orWhereHas('provider', function ($profileQuery) use ($searchTerm) {
                        $profileQuery->where('bio', 'like', $searchTerm);
                    });
                });
            });
        }

        if ($request->filled('service')) {
            $serviceTerm = '%' . $request->service . '%';
            $providersQuery->whereHas('offeredServices', function ($serviceQuery) use ($serviceTerm) {
                $serviceQuery->where('name', 'like', $serviceTerm);
            });
        }

        // Ordenação dos resultados
        if ($request->filled('order')) {
            switch ($request->order) {
                case 'recent':
                    $providersQuery->orderBy('created_at', 'desc');
                    break;
                case 'name':
                    // Ordenar por nome agora é direto, sem necessidade de join
                    $providersQuery->orderBy('name', 'asc');
                    break;
                case 'rating':
                    // Para ordenar por um campo da tabela relacionada, usamos um join
                    $providersQuery->join('provider_profiles', 'users.id', '=', 'provider_profiles.user_id')
                                   ->orderBy('provider_profiles.average_rating', 'desc')
                                   ->select('users.*'); // Garante que a seleção principal seja da tabela users
                    break;
                default:
                    $providersQuery->orderBy('created_at', 'desc');
            }
        }

        $paginatedProviders = $providersQuery->paginate($request->input('limit', 15));

        // O método 'through' transforma cada item da coleção paginada.
        $transformedProviders = $paginatedProviders->through(function ($provider) {
            return [
                'id' => $provider->id,
                'name' => $provider->name,
                'phone' => $provider->phone,
                // Formata os dados do perfil, se existir
                'profile' => $provider->provider ? [
                    'bio' => $provider->provider->bio,
                    'profile_picture_url' => $provider->provider->profile_picture_url,
                    'average_rating' => (float) $provider->provider->average_rating,
                    'total_reviews' => (int) $provider->provider->total_reviews,
                    'total_orders_completed' => (int) $provider->provider->total_orders_completed,
                ] : null,
                // Formata a lista de serviços
                'services' => $provider->offeredServices->map(function ($service) {
                    return [
                        'name' => $service->name,
                        'base_price' => (float) $service->pivot->base_price,
                        'provider_description' => $service->pivot->description,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'providers' => $transformedProviders,
        ]);
    }

    /**
     * Deleta o provider
     * in: id
     * out: provider
     */
    public function delete(Request $request){
        $provider = $request->user()->provider()->with('user')->first();
        $provider->delete();
        return response()->json([
            'success' => true,
            'message' => 'Provider deleted successfully',
        ]);
    }
}
