<?php
namespace App\Service;

use App\Entity\Fournisseur;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class EmailService
{

    private $mailer;
    private $router;

    public function __construct(MailerInterface $mailer, RouterInterface $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public function sendEmail(Fournisseur $fournisseur)
    {

        $str=md5(rand());

        $url = $this->router->generate('app_enchere', [], urlGeneratorInterface::ABSOLUTE_URL);

        $email = (new Email())
            ->from('ramine.agrobis@gmail.com')
            ->to($fournisseur->getEmail())
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html("<p>lien pour l'enchere : $url/$str</p>");

        $this->mailer->send($email);

    }



}