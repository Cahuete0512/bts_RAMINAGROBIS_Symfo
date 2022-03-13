<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnchereController extends AbstractController
{
    #[Route('/enchere', name: 'app_enchere')]
    public function index(): Response
    {
        return $this->render('enchere/index.html.twig', [
            'controller_name' => 'EnchereController',
        ]);
    }
}
