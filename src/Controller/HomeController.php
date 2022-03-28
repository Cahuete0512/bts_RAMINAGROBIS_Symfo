<?php

namespace App\Controller;

use App\Service\ApiServiceGetEnchere;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ApiServiceGetEnchere $apiServiceGetEnchere): Response
    {
        dd($apiServiceGetEnchere->getApiData());

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
