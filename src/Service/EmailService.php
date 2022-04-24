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
    private $doctrine;

    /**
     * @param MailerInterface $mailer
     * @param RouterInterface $router
     * @param ManagerRegistry $doctrine
     */
    public function __construct(MailerInterface $mailer,
                                RouterInterface $router,
                                ManagerRegistry $doctrine)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->doctrine = $doctrine;
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

        $email = (new Email())
            ->from('ramine.agrobis@gmail.com')
            ->to($fournisseur->getEmail())
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html("<p>lien pour l'enchere : $url</p>");

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