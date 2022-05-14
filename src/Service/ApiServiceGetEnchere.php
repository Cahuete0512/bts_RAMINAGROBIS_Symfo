<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiServiceGetEnchere
{

    private $client;

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param $idPanier
     * @return mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
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
