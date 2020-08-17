<?php
namespace App\Services;

use GuzzleHttp\Client as HttpClient;
use App\Models\Transaction;

class EmailService
{
    protected $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function sendConfimation(Transaction $transaction)
    {
        $url = env('EMAIL_SERVICE_URL');

        // TODO: Montar aqui o corpo de email

        $response = $this->httpClient->get($url);
        $data = json_decode($response->getBody(), true);

        if (!isset($data['message'])) {
            throw new \Exception('Error on EmailService');
        }

        return ($data['message'] == 'Enviado');
    }
}
