<?php

namespace App\Controller;

use App\Entity\Enchere;
use App\Form\EnchereType;
use App\Service\ApiServiceGetEnchere;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class EnchereController extends AbstractController
{
    #[Route('/enchere', name: 'app_enchere')]
    public function index(ApiServiceGetEnchere $apiService, Request $request): Response
    {
        //créer une enchere vide
        $enchere = new Enchere();

        //Crééer le formulaire pour cette enchere
        $form = $this->createForm(EnchereType::class, $enchere);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //On récupère l'entity manager
            $em = $this->getDoctrine()->getManager();

            //Je dis à l'entity manager que je veux enregistrer ma soirée
            $em->persist($enchere);

            //je déclenche la requête
            $em->flush();
        }

        return $this->render('enchere/index.html.twig', [
            'data' => $apiService->getApiData(),
            "formulaire"=> $form->createView()
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
