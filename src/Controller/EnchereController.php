<?php

namespace App\Controller;

use App\Entity\Enchere;
use App\Entity\Fournisseur;
use App\Entity\LignePanier;
use App\Entity\SessionEnchere;
use App\Entity\SessionEnchereFournisseur;
use App\Service\ApiServiceGetEnchere;
use App\Service\ApiServicePostCloreEnchere;
use App\Service\CookieService;
use App\Service\EmailService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class EnchereController extends AbstractController
{
    /**
     * @param ApiServiceGetEnchere $apiService
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param EmailService $emailService
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function test(ApiServiceGetEnchere $apiService,
                          Request $request,
                          ManagerRegistry $doctrine,
                          EmailService $emailService): Response
    {
        return $this->redirectToRoute('app_enchere');
    }

    /**
     * @param $cle
     * @param Request $request
     * @param LoggerInterface $logger
     * @param ManagerRegistry $doctrine
     * @param CookieService $cookieService
     * @return Response
     */
    #[Route('/enchere/access/{cle}', name: 'app_acces_enchere')]
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
            return $this->redirectToRoute('app_fournisseur_inexistant');
        }

        $sessionEnchereFournisseur = $fournisseur->getSessionEnchereFournisseurActuelle();

        if($sessionEnchereFournisseur === null || $sessionEnchereFournisseur->getFermee()){
            $logger->info("Aucune session d'enchere trouvée");
            return $this->redirectToRoute('app_session_enchere_inexistante');
        }

        if($request->cookies->get('cle') === null) {
            $logger->info("création du cookie pour la session d'enchere");
            $cookie = new Cookie('cle',
                $sessionEnchereFournisseur->getCleConnexion(),
                $sessionEnchereFournisseur->getSessionEnchere()->getFinEnchere(),
                '/',
                null,
                false,
                false);
            $res = new Response();
            $res->headers->setCookie($cookie);
            $res->send();
        }else{
            $logger->debug("Cookie pour la session d'enchere déjà existant");
        }

        return $this->redirectToRoute('app_enchere');
    }

    /**
     * @param Request $request
     * @param LoggerInterface $logger
     * @param ManagerRegistry $doctrine
     * @return Response
     */
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

    /**
     * @param Request $request
     * @param LoggerInterface $logger
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/enchere/rafraichir', name: 'app_rafraichir_enchere')]
    public function rafraichir(Request $request,
                          LoggerInterface $logger,
                          ManagerRegistry $doctrine): Response
    {
        $cookie = $request->cookies->get('cle');
        $logger->info('cle : ' . $cookie);

        $fournisseurRepo=$doctrine->getRepository(Fournisseur::class);
        $fournisseur = $fournisseurRepo->findOneByCle($cookie);

        $lignePanierRepo=$doctrine->getRepository(LignePanier::class);
        $lignesPaniers=$lignePanierRepo->findByFournisseur($fournisseur);

        $nbLignes = count($lignesPaniers);
        $dataRetour = "{\"data\": [";
        foreach ($lignesPaniers as $index => $ligne){
            $couleur = $ligne->getEncheres()[0] == null ? "rouge" : $ligne->getEncheres()[0]->getCouleur();
            $dataRetour .= "{\"idLignePanier\": ".$ligne->getId().", \"couleur\": \"cercle_$couleur\"}";
            if(++$index < $nbLignes){
                $dataRetour .= ",";
            }
        }
        $dataRetour .= "]}";

        return new JsonResponse($dataRetour, Response::HTTP_OK);
    }

    /**
     * @param LoggerInterface $logger
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param ApiServiceGetEnchere $apiServiceGetEnchere
     * @param EmailService $emailService
     * @return Response
     */
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
            $logger->info(var_dump($panier));

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
                $sessionEnchere->getLignesPaniers()->add($lignePanier);
                $lignePanier->setQuantite($lignePanierRecue->quantite);
                $lignePanier->setReference($lignePanierRecue->produit->reference);
                $lignePanier->setSessionEnchere($sessionEnchere);

                foreach ($lignePanierRecue->produit->fournisseurListe as $fournisseurRecu) {
                    // rechercher si le fournisseur existe en BDD, pour créer la relation
                    $fournisseur = $fournisseurRepo->findOneBy(['societe' => $fournisseurRecu->societe]);
                    $fournisseur->getLignesPaniers()->add($lignePanier);
                    $lignePanier->getFournisseurs()->add($fournisseur);

                    if (!$this->sessionEnchereFournisseurExists($sessionEnchere, $fournisseur)) {
                        $sessionEnchereFournisseur = new SessionEnchereFournisseur();
                        $sessionEnchereFournisseur->setSessionEnchere($sessionEnchere);
                        $sessionEnchereFournisseur->setFournisseur($fournisseur);
                        $sessionEnchereFournisseur->setCleConnexion(md5(rand()));
                        $sessionEnchereFournisseur->setFermee(false);
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

    /**
     * @param SessionEnchere $sessionEnchere
     * @param Fournisseur $fournisseur
     * @return bool
     */
    private function sessionEnchereFournisseurExists(SessionEnchere $sessionEnchere, Fournisseur $fournisseur)
    {
        foreach ($sessionEnchere->getSessionEnchereFournisseurs() as $sessionEnchereFournisseur){
            if($sessionEnchereFournisseur->getFournisseur() === $fournisseur){
                return true;
            }
        }
        return false;
    }

    /**
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param LoggerInterface $logger
     * @return JsonResponse
     */
    #[Route('/enchere/ajouter', name: 'ajouter_enchere', methods: 'POST')]
    public function ajouterEnchere(Request $request,
                                   ManagerRegistry $doctrine,
                                   LoggerInterface $logger): JsonResponse
    {
        $enchereRepo = $doctrine->getRepository(Enchere::class);
        $lignePanierRepo = $doctrine->getRepository(LignePanier::class);
        $fournisseurRepo = $doctrine->getRepository(Fournisseur::class);

        $cookie = $request->cookies->get('cle');
        $data = json_decode($request->getContent());

        $logger->info('cle : ' . $cookie);

        $fournisseur = $fournisseurRepo->findOneByCle($cookie);
        $lignePanier = $lignePanierRepo->find($data->idLigne);

        $derniereEnchereFournisseur = $enchereRepo->findOneBy(["fournisseur" => $fournisseur->getId(), "lignePanier" => $lignePanier->getId()], ["dateEnchere" => "ASC"]);
        if($derniereEnchereFournisseur != null && $derniereEnchereFournisseur->getPrixEnchere() >=  $data->prix){
            return new JsonResponse("{\"erreur\": \"Veuillez encherir une valeur plus élevée que votre dernier prix\"}", Response::HTTP_FORBIDDEN);
        }

        $enchere = new Enchere();
        $enchere->setDateEnchere(new \DateTime('now'));
        $enchere->setFournisseur($fournisseur);
        $enchere->setLignePanier($lignePanier);
        $enchere->setPrixEnchere($data->prix);

        $enchereRepo->add($enchere);
        $doctrine->getManager()->flush();

        $position = $this->comparerEnchere($enchere, $data->idLigne, $doctrine, $logger);
        $couleur= 'cercle_orange';
        if ($position == -1){
            $logger->info('enchere insuffisante');
            $couleur= 'cercle_rouge';
        }elseif ($position == 1){
            $logger->info('enchere la plus haute');
            $couleur= 'cercle_vert';
        }

        return new JsonResponse("{\"idLignePanier\":$data->idLigne,\"couleur\": \"$couleur\"}", Response::HTTP_OK);
    }

    /**
     * @param $monEnchere
     * @param $idLignePanier
     * @param ManagerRegistry $doctrine
     * @return int
     */
    private function comparerEnchere($monEnchere, $idLignePanier, ManagerRegistry $doctrine,
                                     LoggerInterface $logger){
        $lignePanierRepo = $doctrine->getRepository(LignePanier::class);
        $lignePanier = $lignePanierRepo->find($idLignePanier);

        $resultat = 0;
        $count = 0;

        foreach ($lignePanier->getEncheres() as $enchere){
            if($monEnchere->getPrixEnchere() < $enchere->getPrixEnchere()){
                $resultat = -1;
                break;
            }elseif ($monEnchere->getPrixEnchere() == $enchere->getPrixEnchere()){
                $logger->info('count++');
                $count ++;
            }
        }

        $logger->info('count : '.$count);
        if($resultat == 0 && $count == 0){
            $resultat = 1;
        }
        return $resultat;
    }

    /**
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param LoggerInterface $logger
     * @param ApiServicePostCloreEnchere $apiServicePostCloreEnchere
     * @return JsonResponse
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    #[Route('/enchere/clore', name: 'clore_enchere', methods: 'GET')]
    public function clore(Request $request,
                           ManagerRegistry $doctrine,
                           LoggerInterface $logger,
                           ApiServicePostCloreEnchere $apiServicePostCloreEnchere): Response
    {

        $cle = $request->cookies->get('cle');

        $fournisseurRepo=$doctrine->getRepository(Fournisseur::class);
        $fournisseur = $fournisseurRepo->findOneByCle($cle);

        $sessionEnchereFournisseur = $fournisseur->getSessionEnchereFournisseurActuelle();

        // on débute un nouveau "fichier csv"
        $lignesCsv = array();

        // pour chaque LignePanier de ce fournisseur
        foreach ($fournisseur->getLignesPaniers() as $lignePanier){
            if($sessionEnchereFournisseur->getSessionEnchere()->getLignesPaniers()->contains($lignePanier)){
                // si la LignePanier du fournisseur est présente sur cette session

                $enchereFournisseurMax = null;
                // on itere sur les encheres de la LignePanier
                foreach ($lignePanier->getEncheres() as $enchere){
                    if($fournisseur->getEncheres()->contains($enchere)) {
                        if ($enchereFournisseurMax == null || $enchere->getPrixEnchere() > $enchereFournisseurMax->getPrixEnchere()) {
                            $enchereFournisseurMax = $enchere;
                        }
                    }
                }
                if($enchereFournisseurMax != null) {
                    // on ajoute une ligne au fichier
                    $lignesCsv[] = $lignePanier->getReference().';'.$lignePanier->getQuantite().';'.$enchereFournisseurMax->getPrixEnchere();
                }

            }
        }
        if(!empty($lignesCsv)) {
            // on poste le "fichier" à l'appli C# si le tableau n'est pas vide
            $apiServicePostCloreEnchere->clore($fournisseur->getSociete(), $lignesCsv);
        }

        $sessionEnchereFournisseur->setFermee(true);
        $doctrine->getManager()->flush();


        $res = new Response();
        $res->headers->clearCookie('cle');
        $res->send();

        return $this->redirectToRoute('app_session_enchere_inexistante');
    }
}
