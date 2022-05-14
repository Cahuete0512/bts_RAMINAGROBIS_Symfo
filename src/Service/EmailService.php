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
        $debutEnchere = $sessionEnchere->getDebutEnchere()->format('d-m-Y H:i:s');
        $finEnchere = $sessionEnchere->getFinEnchere()->format('d-m-Y H:i:s');
        $email = (new Email())
            ->from('ramine.agrobis@gmail.com')
            ->to($fournisseur->getEmail())
            ->subject('Votre session d enchere est ouverte!')
            ->text('Sending emails is fun again!')
            ->html("<p>Bonjour, </p>
                          <p> Veuillez trouver ci-joint votre lien pour accéder aux enchères : $url pour la semaine $num du $debutEnchere au $finEnchere</p>
                          <p> Ce lien se détruira à la fin de cette semaine le $finEnchere</p>
                          <p> Pensez à bien clôturer vos enchères avant la fin de cette période pour que vos prix soient bien récupérés et enregistrés par la centrale RAMINAGROBIS
                          <br><p> Toute notre équipe vous souhaite une bonne journée</p>
                          <br><p> Coridalement</p>
                          <br><br><p>RAMINAGROBIS</p>");

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