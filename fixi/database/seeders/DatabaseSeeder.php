<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\User;
use App\Models\Address;
use App\Models\ClientProfile;
use App\Models\Order;
use App\Models\OrderQuote;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\ProviderService;
use App\Models\Review;
use App\Models\ReviewComment;
use App\Models\Service;
use App\Models\ServiceCategory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
       // 1. Cria um usuário Administrador
        $this->command->info('Criando usuário Administrador...');
        User::factory()->asAdmin()->create([
            'name' => 'Gabriel Maciel',
            'email' => 'gabriel@nesos.com.br',
            'password' => Hash::make('password'),
        ]);
        ClientProfile::factory()->create([
            'user_id' => 1,
        ]);
        // 2. Cria usuários Clientes
        $this->command->info('Criando usuários Clientes...');
        $clients = User::factory(200)->asClient()->create();

        // 3. Cria usuários Prestadores de Serviço
        $this->command->info('Criando usuários Prestadores de Serviço...');
        $providers = User::factory(80)->asProvider()->create();

        // 4. Cria Categorias e Serviços
        $this->command->info('Criando Categorias e Serviços...');
        $this->seedCategoriesAndServices();
        $services = Service::all();

        // 5. Associa Serviços aos Prestadores
        $this->command->info('Associando serviços aos prestadores...');
        $providers->each(function (User $provider) use ($services) {
            $servicesToAssign = $services->random(rand(2, 6))->pluck('id');
            foreach ($servicesToAssign as $serviceId) {
                ProviderService::factory()->create([
                    'provider_id' => $provider->id,
                    'service_id' => $serviceId,
                ]);
            }
        });

        // 6. Cria Pedidos, Orçamentos, Pagamentos e Avaliações
        $this->command->info('Criando Pedidos e dados relacionados...');
        // **CORREÇÃO**: Pega todas as combinações válidas de prestador-serviço primeiro.
        $providerServices = ProviderService::with(['provider', 'service'])->get();

        $clients->each(function (User $client) use ($providers, $providerServices) {
            if ($providerServices->isEmpty()) {
                $this->command->error('Nenhuma associação de serviço/prestador encontrada. Pulando criação de pedidos.');
                return;
            }
            // Cria de 1 a 10 pedidos para cada cliente
            for ($i = 0; $i < rand(1, 5); $i++) {
                $providerService = $providerServices->random();
                $provider = $providerService->provider;
                $service = $providerService->service;

                $isCompleted = fake()->boolean(40); // 40% de chance do pedido ser concluído

                $order = Order::factory()
                    ->for($client, 'client')
                    ->for($provider, 'provider')
                    ->for($service)
                    ->create([
                        'status' => $isCompleted ? OrderStatus::COMPLETED : fake()->randomElement(OrderStatus::cases())->value,
                        'completed_at' => $isCompleted ? now() : null,
                    ]);

                // Se o pedido não foi concluído, cria orçamentos de outros prestadores
                if (! $isCompleted) {
                    $otherProviders = $providers->where('id', '!=', $provider->id)->random(rand(1, 4));
                    $otherProviders->each(function (User $otherProvider) use ($order) {
                        OrderQuote::factory()->for($order)->for($otherProvider, 'provider')->create();
                    });
                } else {
                    // Se o pedido foi concluído, cria uma avaliação, um pagamento e um repasse (payout)
                    if(fake()->boolean(60))
                    {
                        Review::factory()->create([
                            'order_id' => $order->id,
                            'client_id' => $client->id,
                            'provider_id' => $provider->id,
                        ]);

                        // Cria comentários para a avaliação com um 50% de chance por cada comentário
                        while(fake()->boolean(50)){
                            ReviewComment::factory()->create([
                                'user_id' => User::inRandomOrder()->first()->id,
                                'review_id' => Review::inRandomOrder()->first()->id,
                                'comment' => fake()->paragraph(),
                            ]);
                        }
                    }

                    $pay = Payment::factory()->create([
                        'order_id' => $order->id,
                        'client_id' => $client->id,
                        'provider_id' => $provider->id,
                        'amount' => $order->final_price,
                    ]);

                    //se o pagamento foi aprovado, cria um repasse
                    if($pay->status == PaymentStatus::SUCCEEDED){
                        Payout::factory()->create([
                            'provider_id' => $provider->id,
                            'amount' => $order->provider_fee,
                        ]);
                    }

                }
            }
        });

        $this->command->info('Banco de dados populado com sucesso!');
    }

    /**
     * Cria um conjunto de categorias e serviços realistas.
     */
    protected function seedCategoriesAndServices(): void
    {
        $structure = [
            'Reformas e Reparos' => ['Pintor', 'Eletricista', 'Encanador', 'Pedreiro', 'Montador de Móveis', 'Carpinteiro', 'Jardineiro'],
            'Serviços Domésticos' => ['Diarista', 'Cozinheira', 'Passadeira', 'Jardineiro', 'Babá','Faz tudo','Marido de aluguel'],
            'Tecnologia' => ['Técnico de Informática', 'Conserto de Celular', 'Desenvolvedor Web', 'Suporte Remoto', 'Instalação de Software'],
            'Eventos' => ['Fotógrafo', 'DJ', 'Barman', 'Churrasqueiro', 'Organizador de Festas', 'Bartender','Local de Eventos', 'Som, Luz e Imagem'],
            'Beleza e Moda' => ['Cabeleireiro', 'Manicure e Pedicure', 'Maquiador', 'Esteticista', 'Personal Stylist', 'Barbeiro'],
            'Aulas e Consultoria' => ['Professor Particular', 'Consultor Financeiro', 'Coach de Carreira', 'Professor de Música', 'Professor de séries inicias', 'Professor de ensino médio','Professor de idiomas'],
        ];

        foreach ($structure as $categoryName => $services) {
            $category = ServiceCategory::factory()->create(['name' => $categoryName]);
            foreach ($services as $serviceName) {
                Service::factory()->create(['category_id' => $category->id, 'name' => $serviceName]);
            }
        }
    }
}
