<?php
namespace App\Services;

use GuzzleHttp\Client as HttpClient;

class PaymentAuthorizationService
{
    protected $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function verify(string $document)
    {
        $url = env('PAYMENT_AUTHORIZATION_URL');
        $response = $this->httpClient->get($url . '/' . $document);
        $data = json_decode($response->getBody(), true);

        if (!isset($data['message'])) {
            throw new \Exception('Error on PaymentAuthorizationService');
        }

        return ($data['message'] == 'Autorizado');
    }
}
