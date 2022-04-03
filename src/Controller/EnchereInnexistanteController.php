<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnchereInnexistanteController extends AbstractController
{
    #[Route('/enchere/innexistante', name: 'app_enchere_innexistante')]
    public function index(): Response
    {

        return $this->render('enchere_innexistante/enchereInnexistante.html.twig', [
            'controller_name' => 'EnchereInnexistanteController',
        ]);
    }
}
