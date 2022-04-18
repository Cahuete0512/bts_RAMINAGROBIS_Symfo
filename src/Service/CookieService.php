<?php
namespace App\Service;

use App\Entity\Fournisseur;
use App\Entity\SessionEnchereFournisseur;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

class CookieService
{
    public function createCookie(Fournisseur $fournisseur)
    {
        $sessionEnchereFournisseur = $this->getSessionEnchereFournisseurActuel($fournisseur);

        $cookie = new Cookie('cookie',
                              $sessionEnchereFournisseur->getCleConnexion(),
                              $sessionEnchereFournisseur->getSessionEnchere()->getFinEnchere());
    }

    private function getSessionEnchereFournisseurActuel(Fournisseur $fournisseur): ?SessionEnchereFournisseur
    {
        foreach ($fournisseur->getSessionEnchereFournisseurs() as $sessionEnchereFournisseur){
            $sessionEnchereFournisseur->getSessionEnchere();
            $now = new \DateTime('now');
            if($now >= $sessionEnchereFournisseur->getSessionEnchere()->getDebutEnchere()
                && $now <= $sessionEnchereFournisseur->getSessionEnchere()->getFinEnchere()){
                return $sessionEnchereFournisseur;
            }
        }
        return null;
    }
}