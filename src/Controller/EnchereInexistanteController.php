<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnchereInexistanteController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/enchere_inexistante', name: 'app_enchere_inexistante')]
    public function index(): Response
    {

        return $this->render('enchere_inexistante/enchereInexistante.html.twig');
    }
}
