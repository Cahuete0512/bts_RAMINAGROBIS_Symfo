<?php

namespace App\Controller;

use App\Entity\Enchere;
use App\Entity\Fournisseur;
use App\Entity\LignePanier;
use App\Entity\SessionEnchere;
use App\Entity\SessionEnchereFournisseur;
use App\Form\EnchereType;
use App\Service\ApiServiceGetEnchere;
use App\Service\EmailService;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class EnchereController extends AbstractController
{
    //TODO:à retirer après fin d'utilisation
    #[Route('/test', name: 'test')]
    public function test(ApiServiceGetEnchere $apiService,
                          Request $request,
                          ManagerRegistry $doctrine,
                          EmailService $emailService): Response
    {
        $fournisseur = new Fournisseur();
        $fournisseur->setEmail("magalie.contant@epsi.fr");
        $emailService->sendEmail($fournisseur);

        return $this->render('enchere/enchere.html.twig', []);
    }



    #[Route('/enchere', name: 'app_enchere')]
    public function index(ApiServiceGetEnchere $apiService,
                          Request $request,
                          ManagerRegistry $doctrine,
                          EmailService $emailService): Response
    {
        //créer une enchere vide
        $enchere = new Enchere();

        //Créer le formulaire pour cette enchere
        $form = $this->createForm(EnchereType::class, $enchere);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //On récupère l'entity manager
            $em = $doctrine->getManager();

            $enchere->setDateEnchere(new \DateTime("now"));
            //Je dis à l'entity manager que je veux enregistrer mon enchere
            $em->persist($enchere);

            //je déclenche la requête
            $em->flush();
        }
        // TODO voir comment on récupère le fournisseur (pour le moment on récupère un arbitrairement)
        $fournisseurRepo=$doctrine->getRepository(Fournisseur::class);
        $fournisseur = $fournisseurRepo->find(547);

        $lignePanierRepo=$doctrine->getRepository(LignePanier::class);
        $lignesPaniers=$lignePanierRepo->findByFournisseur($fournisseur);

        // TODO contrôle à revoir/compléter
        if($lignesPaniers==null){
            return $this->redirectToRoute('app_enchere_inexistante');
        }

        return $this->render('enchere/enchere.html.twig', [
            'lignesPaniers' => $lignesPaniers,
            'fournisseur' => $fournisseur,
            "formulaire"=> $form->createView()
        ]);
    }

    #[Route('/lancerEnchere', name: 'app_lancer_enchere', methods: 'POST')]
    public function lancerEnchere(
                                LoggerInterface $logger,
                                Request $request,
                                ManagerRegistry $doctrine,
                                ApiServiceGetEnchere $apiServiceGetEnchere,
                                EmailService $emailService): Response
    {
        $logger->info($request->getContent());

        // on récupère l'ID du panier dans la requete reçue du C#
        $datas = json_decode($request->getContent());

        $logger->info($datas->idPanier);
        $logger->info($datas->debutPeriode);
        $logger->info($datas->finPeriode);

        $panier = $apiServiceGetEnchere->getApiData($datas->idPanier);

        // on enregistre les fournisseurs
        $fournisseurRepo = $doctrine->getRepository(Fournisseur::class);
        $this->creerFournisseurs($panier, $fournisseurRepo, $logger);

        $sessionEnchere = new SessionEnchere();

        $sessionEnchere->setIdPanier($panier->id);
        $sessionEnchere->setNumeroSemaine($panier->numeroSemaine);
        $sessionEnchere->setDebutEnchere(\DateTime::createFromFormat('d/m/Y H:i:s', $datas->debutPeriode));
        $sessionEnchere->setFinEnchere(\DateTime::createFromFormat('d/m/Y H:i:s', $datas->finPeriode));

        foreach ($panier->lignesPaniersGlobauxList as $lignePanierRecue){
            $lignePanier = new LignePanier();
            $sessionEnchere->getLignePaniers()->add($lignePanier);
            $lignePanier->setQuantite($lignePanierRecue->quantite);
            $lignePanier->setReference($lignePanierRecue->produit->reference);
            $lignePanier->setSessionEnchere($sessionEnchere);

            foreach ($lignePanierRecue->produit->fournisseurListe as $fournisseurRecu){
                // rechercher si le fournisseur existe en BDD, pour créer la relation
                $fournisseur = $fournisseurRepo->findOneBy(['societe' => $fournisseurRecu->societe]);
                $fournisseur->getLignePaniers()->add($lignePanier);
                $lignePanier->getFournisseurs()->add($fournisseur);

                if(!$this->sessionEnchereFournisseurExists($sessionEnchere, $fournisseur)) {
                    $sessionEnchereFournisseur = new SessionEnchereFournisseur();
                    $sessionEnchereFournisseur->setSessionEnchere($sessionEnchere);
                    $sessionEnchereFournisseur->setFournisseur($fournisseur);
                    $sessionEnchereFournisseur->setCleConnexion(md5(rand()));
                    $sessionEnchere->addSessionEnchereFournisseur($sessionEnchereFournisseur);
                }
            }
        }
        $sessionEnchereRepo = $doctrine->getRepository(SessionEnchere::class);
        $sessionEnchereRepo->add($sessionEnchere);


        $logger->info('Envoie des emails ');
        $fournisseurRepo = $doctrine->getRepository(Fournisseur::class);
        $fournisseurs = $fournisseurRepo->findBySessionEnchere($sessionEnchere);

        foreach ($fournisseurs as $fournisseur){
            $emailService->sendEmail($fournisseur, $sessionEnchere);
        }

        return new Response("OK");
    }

    /**
     * @param mixed $panier
     * @param \Doctrine\Persistence\ObjectRepository $fournisseurRepo
     * @param LoggerInterface $logger
     */
    public function creerFournisseurs(mixed $panier, \Doctrine\Persistence\ObjectRepository $fournisseurRepo, LoggerInterface $logger): void
    {
        foreach ($panier->lignesPaniersGlobauxList as $lignePanierRecue) {
            foreach ($lignePanierRecue->produit->fournisseurListe as $fournisseurRecu) {
                $fournisseur = $fournisseurRepo->findOneBy(['societe' => $fournisseurRecu->societe]);
                if ($fournisseur == null) {
                    $fournisseur = new Fournisseur();
                    $fournisseur->setSociete($fournisseurRecu->societe);
                    $fournisseur->setEmail($fournisseurRecu->email);
                    $fournisseurRepo->add($fournisseur);
                    $logger->info('Nouveau fournisseur enregistré : ' . $fournisseur->getSociete());
                } else {
                    $logger->info('fournisseur trouvé : ' . $fournisseur->getSociete());
                }
            }
        }
    }

    private function sessionEnchereFournisseurExists(SessionEnchere $sessionEnchere, Fournisseur $fournisseur)
    {
        foreach ($sessionEnchere->getSessionEnchereFournisseurs() as $sessionEnchereFournisseur){
            if($sessionEnchereFournisseur->getFournisseur() === $fournisseur){
                return true;
            }
        }
        return false;
    }
}
