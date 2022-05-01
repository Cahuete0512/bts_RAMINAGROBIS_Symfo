<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiServicePostCloreEnchere
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
    public function clore(string $societe, array $contenu): mixed
    {
        $response = $this->client->request(
            'POST',
            'https://localhost:5001/Offres/clientEnchere/'.$societe,
            [ 'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($contenu)]
        );

        // $contentType = 'application/json'
        $content = json_decode($response->getContent());

        return $content;

    }
}
