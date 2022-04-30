<?php
namespace App\Service;

use App\Entity\Fournisseur;
use App\Entity\SessionEnchere;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class EmailService
{

    private $mailer;
    private $router;

    /**
     * @param MailerInterface $mailer
     * @param RouterInterface $router
     */
    public function __construct(MailerInterface $mailer,
                                RouterInterface $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }

    /**
     * @param Fournisseur $fournisseur
     * @param SessionEnchere $sessionEnchere
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendEmail(Fournisseur $fournisseur, SessionEnchere $sessionEnchere)
    {

        $cle = $this->getCleSession($fournisseur, $sessionEnchere);

        $url = $this->router->generate('app_acces_enchere', ['cle' => $cle], urlGeneratorInterface::ABSOLUTE_URL);

        $num = $sessionEnchere->getNumeroSemaine();
        $email = (new Email())
            ->from('ramine.agrobis@gmail.com')
            ->to($fournisseur->getEmail())
            ->subject('Votre session d enchere est ouverte!')
            ->text('Sending emails is fun again!')
            ->html("<p>lien pour l'enchere : $url pour la semaine : $num</p>");

        $this->mailer->send($email);

    }

    /**
     * @param Fournisseur $fournisseur
     * @param SessionEnchere $sessionEnchere
     * @return string|null
     */
    private function getCleSession(Fournisseur $fournisseur, SessionEnchere $sessionEnchere): ?string
    {
        foreach ($sessionEnchere->getSessionEnchereFournisseurs() as $sessionEnchereFournisseur){
            if($sessionEnchereFournisseur->getFournisseur() === $fournisseur){
                return $sessionEnchereFournisseur->getCleConnexion();
            }
        }

        return null;
    }
}