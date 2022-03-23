<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnchereController extends AbstractController
{
    #[Route('/enchere', name: 'app_enchere')]
    public function index(ApiService $apiService): Response
    {
        return $this->render('enchere/index.html.twig', [
            'data' => $apiService->getApiData(),
        ]);
    }
}
