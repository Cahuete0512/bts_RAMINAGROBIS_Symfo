<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnchereInexistanteController extends AbstractController
{
    #[Route('/enchere/inexistante', name: 'app_enchere_inexistante')]
    public function index(): Response
    {

        return $this->render('enchere_inexistante/enchereInexistante.html.twig', [
            'controller_name' => 'EnchereInexistanteController',
        ]);
    }
}
