<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Busca de serviços
     * in: [search, category, order(recent, name), page, limit]
     * out: [services]
     */
    public function search(Request $request){
        $servicesQuery = Service::query()
            ->with(['providers', 'category']);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $servicesQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm)
                ->orwhere('description', 'like', $searchTerm)
                ->orWhereHas('providers', function ($providerQuery) use ($searchTerm) {
                    // Busca no nome do prestador (users.name)
                    $providerQuery->where('name', 'like', $searchTerm)
                        // CORREÇÃO: Em vez de buscar 'bio' diretamente,
                        // usamos outro 'whereHas' para entrar na relação 'providerProfile'
                        ->orWhereHas('provider', function ($profileQuery) use ($searchTerm) {
                            // Agora a busca é feita na tabela 'provider_profiles'
                            $profileQuery->where('bio', 'like', $searchTerm);
                        });
                });
            });
        }

        if ($request->filled('category')) {
            $categoryTerm = '%' . $request->category . '%';
            $servicesQuery->whereHas('category', function ($categoryQuery) use ($categoryTerm) {
                $categoryQuery->where('name', 'like', $categoryTerm);
            });
        }

        if ($request->filled('order')) {
            switch ($request->order) {
                case 'recent':
                    $servicesQuery->orderBy('created_at', 'desc');
                    break;
                case 'name':
                    $servicesQuery->orderBy('name', 'asc');
                    break;
                default:
                    $servicesQuery->orderBy('created_at', 'desc');
                    break;
            }
        }

        //Lista de serviços
        $services = $servicesQuery->paginate($request->limit ?? 10);

        //Transforma os dados
        $transformedServices = $services->map(function ($service) {
            return [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'providers' => $service->providers()->orderBy('name')->select('name','email','phone')->take(5)->get(),
                'category' => [
                    'id' => $service->category->id,
                    'name' => $service->category->name,
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'services' => $transformedServices
        ]);
    }

    /**
     * Get service by name
     * in: [name, max_providers, order(recent, rating, name, price_asc, price_desc)]
     * out: [service]
     */
    public function get(Request $request){
         $request->validate([
            'service' => 'required|string',
            'order' => 'sometimes|string|in:recent,rating,name,price_asc,price_desc',
            'max_providers' => 'sometimes|integer|min:1',
        ]);

        $service = Service::where('name', 'like', '%'.$request->service.'%')->with('category')->firstOrFail();
        $providersQuery = $service->providers();

        if ($request->filled('order')) {
            switch ($request->order) {
                case 'recent':
                    $providersQuery->orderBy('users.created_at', 'desc');
                    break;
                case 'name':
                    $providersQuery->orderBy('users.name', 'asc');
                    break;
                case 'rating':
                    // Faz um JOIN com a tabela de perfis para poder ordenar pela nota
                    $providersQuery->join('provider_profiles', 'users.id', '=', 'provider_profiles.user_id')
                        ->orderBy('provider_profiles.average_rating', 'desc')
                        ->select('users.*'); // Seleciona apenas as colunas de 'users' para evitar conflitos
                    break;
                case 'price_asc':
                case 'price_desc':
                    // Ordena pelo 'base_price' que está na tabela pivô 'provider_services'
                    $direction = ($request->order === 'price_asc') ? 'asc' : 'desc';
                    $providersQuery->orderBy('provider_services.base_price', $direction);
                    break;
            }
        }

        if ($request->filled('max_providers')) {
            $providersQuery->limit($request->max_providers);
        }

        $providers = $providersQuery->with('provider')->get();

        $formattedProviders = $providers->map(function ($provider) {
            return [
                'id' => $provider->id,
                'name' => $provider->name,
                'email' => $provider->email,
                'phone' => $provider->phone,
                'base_price_for_service' => (float) $provider->pivot->base_price,
                'bio' => $provider->provider ? $provider->provider->bio : null,
                'profile' => $provider->provider ? [
                    'average_rating' => (float) $provider->provider->average_rating,
                    'total_reviews' => (int) $provider->provider->total_reviews,
                ] : null,
            ];
        });
        return response()->json([
            'success' => true,
            'service' => $service,
            'providers' => $formattedProviders,
        ]);
    }
}
