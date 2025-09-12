<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsaasService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = 'https://api-sandbox.asaas.com/v3'; // Ajuste para produção se necessário
        $this->token = '$aact_hmlg_000MzkwODA2MWY2OGM3MWRlMDU2NWM3MzJlNzZmNGZhZGY6OjFmMzdjMmM3LTM0OTAtNGQ0Zi05YjQxLWNkN2Y1NmQ5ZjVhODo6JGFhY2hfMmNlZjE2ZjItNjZjZC00YjJhLWEyYzQtZWE2N2JjNGZlNDRj';
    }

    protected function request($method, $uri, $data = [])
    {
        try {
			// Configura os headers da requisição
			$headers = [
				'access_token' => $this->token,
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
			];

			// Faz a requisição HTTP
			$response = Http::withHeaders($headers)
				->{$method}($this->baseUrl . $uri, $data)
				->throw()
				->json();

			return $response;
		} catch (\Exception $e) {
			// Loga o erro em caso de falha na requisição
			Log::channel('asaas')->error('Erro na requisição HTTP:', [
				'method' => $method,
				'url' => $this->baseUrl . $uri,
				'headers' => $headers,
				'body' => $data,
				'error' => $e->getMessage(),
			]);
			return json_encode([
				'error' => 'Erro ao processar a requisição: ' . $e->getMessage(),
				'trace' => $e->getTraceAsString(),
				'code' => $e->getCode(),
				'message' => $e->getMessage(),
			]);
		}
    }

    // Métodos para Cobranças
    public function createPayment(array $data)
    {
        return $this->request('post', '/payments', $data);
    }

    public function getPayment(string $id)
    {
        return $this->request('get', "/payments/{$id}");
    }

    public function updatePayment(string $id, array $data)
    {
        return $this->request('put', "/payments/{$id}", $data);
    }

    public function cancelPayment(string $id)
    {
        return $this->request('delete', "/payments/{$id}");
    }

    // Métodos para Customers
    public function createCustomer(array $data)
    {
        return $this->request('post', '/customers', $data);
    }

    public function getCustomer(string $id)
    {
        return $this->request('get', "/customers/{$id}");
    }

    public function updateCustomer(string $id, array $data)
    {
        return $this->request('put', "/customers/{$id}", $data);
    }

    public function deleteCustomer(string $id)
    {
        return $this->request('delete', "/customers/{$id}");
    }

    /**
     * Encontra um cliente por campo e valor.
     *
     * @param  array  $data  ['field' => 'nome', 'value' => 'Joao']
     * @param  int  $limit  Limite de quantidade de resultados. Default: 1
     * @param  int  $offset  Offset dos resultados. Default: 0
     * @return array
     */
	public function findCustomer($data, $limit = 1, $offset = 0)
	{
		Log::debug('Buscando cliente no Asaas', [
			'field' => $data['field'],
			'value' => $data['value'],
			'limit' => $limit,
			'offset' => $offset,
		]);
		return $this->request('get', "/customers?{$data['field']}={$data['value']}&limit={$limit}&offset={$offset}");
	}

	// Métodos para Transferências PIX
	/**
	 * Cria uma transferência PIX.
	 * @param array $data:[
	 * 		'value' => 100.00, // Valor da transferência
	 * 		'operationType' => 'PIX', // Tipo de operação (PIX / TED)
	 * 		'externalReference' => 'uuid da comissão',
	 *   	'pixAddressKey' => 'Chave PIX',
	 * 		'pixAddressType' => 'CPF', // Tipo de chave PIX (CPF, CNPJ, EMAIL, PHONE, EVP)
	 * 		'description' => 'Descrição da transferência',	 *
	 * 	]
	 */
	public function createTransfer(array $data)
	{
		return $this->request('post', '/transfers', $data);
	}

	public function getTransfer(string $id)
	{
		return $this->request('get', "/transfers/{$id}");
	}


}
