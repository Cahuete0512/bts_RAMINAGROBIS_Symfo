<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiServiceGetEnchere
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getApiData($idPanier): mixed
    {
        $response = $this->client->request(
            'GET',
            'https://localhost:5001/PaniersGlobaux/panier/byIdPanier/'.$idPanier
        );

        // $contentType = 'application/json'
        $content = json_decode($response->getContent());

        return $content;

    }
}
