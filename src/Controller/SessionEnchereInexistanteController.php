<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionEnchereInexistanteController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/session_enchere_inexistante', name: 'app_session_enchere_inexistante')]
    public function index(): Response
    {

        return $this->render('session_enchere_inexistante/sessionEnchereInexistante.html.twig');
    }
}
