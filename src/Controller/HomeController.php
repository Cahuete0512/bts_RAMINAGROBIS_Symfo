<?php

namespace App\Controller;

use App\Service\ApiServiceGetEnchere;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @param ApiServiceGetEnchere $apiServiceGetEnchere
     * @return Response
     */
    #[Route('/', name: 'enchere')]
    public function index(ApiServiceGetEnchere $apiServiceGetEnchere): Response
    {

        return $this->render('enchere/enchere.html.twig', [
            'controller_name' => 'EncherController',
        ]);
    }
}
