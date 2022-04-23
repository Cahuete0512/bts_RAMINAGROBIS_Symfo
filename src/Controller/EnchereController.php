<?php

namespace App\Controller;

use App\Entity\Enchere;
use App\Entity\Fournisseur;
use App\Entity\LignePanier;
use App\Entity\SessionEnchere;
use App\Entity\SessionEnchereFournisseur;
use App\Form\EnchereType;
use App\Form\SessionEnchereType;
use App\Service\ApiServiceGetEnchere;
use App\Service\CookieService;
use App\Service\EmailService;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
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



    #[Route('/enchere/{cle}', name: 'app_acces_enchere')]
    public function accederEnchere($cle,
                          Request $request,
                          LoggerInterface $logger,
                          ManagerRegistry $doctrine,
                          CookieService $cookieService): Response
    {

        $fournisseurRepo=$doctrine->getRepository(Fournisseur::class);
        $fournisseur = $fournisseurRepo->findOneByCle($cle);

        if($fournisseur === null){
            $logger->info("Aucun fournisseur trouvé");
            // TODO : faire une autre page d'erreur personnalisée pour session inexistante
            return $this->redirectToRoute('app_enchere_inexistante');
        }

        $sessionEnchereFournisseur = $fournisseur->getSessionEnchereFournisseurActuelle();
        if($sessionEnchereFournisseur === null){
            $logger->info("Aucune session d'enchere trouvée");
            // TODO : faire une autre page d'erreur personnalisée pour session inexistante
            return $this->redirectToRoute('app_enchere_inexistante');
        }

        if($request->cookies->get('cle') === null) {
            $logger->info("création du cookie pour la session d'enchere");
            $cookie = new Cookie('cle', $sessionEnchereFournisseur->getCleConnexion(), $sessionEnchereFournisseur->getSessionEnchere()->getFinEnchere());
            $res = new Response();
            $res->headers->setCookie($cookie);
            $res->send();
        }else{
            $logger->debug("Cookie pour la session d'enchere déjà existant");
        }

        return $this->redirectToRoute('app_enchere');
    }



    #[Route('/enchere', name: 'app_enchere')]
    public function index(Request $request,
                          LoggerInterface $logger,
                          ManagerRegistry $doctrine): Response
    {
        $cookie = $request->cookies->get('cle');
        $logger->info('cle : ' . $cookie);

        $fournisseurRepo=$doctrine->getRepository(Fournisseur::class);
        $fournisseur = $fournisseurRepo->findOneByCle($cookie);

        $lignePanierRepo=$doctrine->getRepository(LignePanier::class);
        $lignesPaniers=$lignePanierRepo->findByFournisseur($fournisseur);

        if($lignesPaniers==null){
            $logger->info("Aucune ligne de panier trouvée");
            return $this->redirectToRoute('app_enchere_inexistante');
        }

        return $this->render('enchere/enchere.html.twig', [
            'lignesPaniers' => $lignesPaniers,
            'fournisseur' => $fournisseur
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

        $sessionEnchereRepo = $doctrine->getRepository(SessionEnchere::class);
        $panier = $sessionEnchereRepo->findOneBy(array('idPanier' => $datas->idPanier));

        if($panier === null) {
            $logger->info("Création de la session d'enchère");

            $panier = $apiServiceGetEnchere->getApiData($datas->idPanier);

            // on enregistre les fournisseurs
            $fournisseurRepo = $doctrine->getRepository(Fournisseur::class);
            $this->creerFournisseurs($panier, $fournisseurRepo, $logger);

            $sessionEnchere = new SessionEnchere();

            $sessionEnchere->setIdPanier($panier->id);
            $sessionEnchere->setNumeroSemaine($panier->numeroSemaine);
            $sessionEnchere->setDebutEnchere(\DateTime::createFromFormat('d/m/Y H:i:s', $datas->debutPeriode));
            $sessionEnchere->setFinEnchere(\DateTime::createFromFormat('d/m/Y H:i:s', $datas->finPeriode));

            foreach ($panier->lignesPaniersGlobauxList as $lignePanierRecue) {
                $lignePanier = new LignePanier();
                $sessionEnchere->getLignePaniers()->add($lignePanier);
                $lignePanier->setQuantite($lignePanierRecue->quantite);
                $lignePanier->setReference($lignePanierRecue->produit->reference);
                $lignePanier->setSessionEnchere($sessionEnchere);

                foreach ($lignePanierRecue->produit->fournisseurListe as $fournisseurRecu) {
                    // rechercher si le fournisseur existe en BDD, pour créer la relation
                    $fournisseur = $fournisseurRepo->findOneBy(['societe' => $fournisseurRecu->societe]);
                    $fournisseur->getLignePaniers()->add($lignePanier);
                    $lignePanier->getFournisseurs()->add($fournisseur);

                    if (!$this->sessionEnchereFournisseurExists($sessionEnchere, $fournisseur)) {
                        $sessionEnchereFournisseur = new SessionEnchereFournisseur();
                        $sessionEnchereFournisseur->setSessionEnchere($sessionEnchere);
                        $sessionEnchereFournisseur->setFournisseur($fournisseur);
                        $sessionEnchereFournisseur->setCleConnexion(md5(rand()));
                        $sessionEnchere->addSessionEnchereFournisseur($sessionEnchereFournisseur);
                    }
                }
            }

            $sessionEnchereRepo->add($sessionEnchere);


            $logger->info('Envoie des emails ');
            $fournisseurRepo = $doctrine->getRepository(Fournisseur::class);
            $fournisseurs = $fournisseurRepo->findBySessionEnchere($sessionEnchere);

            foreach ($fournisseurs as $fournisseur) {
                $emailService->sendEmail($fournisseur, $sessionEnchere);
            }
        }else{
            $logger->info("La session d'enchère existe déjà");
            return new Response("Session déjà lancée", 410);
        }

        return new Response("OK", 200);
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
