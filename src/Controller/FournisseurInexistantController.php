<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FournisseurInexistantController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/fournisseur_inexistant', name: 'app_fournisseur_inexistant')]
    public function index(): Response
    {

        return $this->render('fournisseur_inexistant/fournisseurInexistant.html.twig');
    }
}
