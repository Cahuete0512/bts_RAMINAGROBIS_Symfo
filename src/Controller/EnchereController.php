<?php

namespace App\Controller;

use App\Service\ApiServiceGetEnchere;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class EnchereController extends AbstractController
{
    #[Route('/enchere', name: 'app_enchere')]
    public function index(ApiServiceGetEnchere $apiService): Response
    {
        return $this->render('enchere/index.html.twig', [
            'data' => $apiService->getApiData(),
        ]);
    }

    #[Route('/lancerEnchere', name: 'app_lancer_enchere', methods: 'POST')]
    public function lancerEnchere(LoggerInterface $logger, Request $request): Response
    {
        $logger->info($request->getContent());


        $logger->info('Envoie des emails ');

        return new Response(null, 200);
    }
}
