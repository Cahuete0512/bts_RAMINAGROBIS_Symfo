<?php

namespace App\Entity;

use App\Repository\SessionEnchereFournisseurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionEnchereFournisseurRepository::class)]
class SessionEnchereFournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $cleConnexion;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private $fermee;

    #[ORM\ManyToOne(targetEntity: Fournisseur::class, inversedBy: 'sessionEnchereFournisseurs')]
    #[ORM\JoinColumn(nullable: false)]
    private $fournisseur;

    #[ORM\ManyToOne(targetEntity: SessionEnchere::class, inversedBy: 'sessionEnchereFournisseurs')]
    #[ORM\JoinColumn(nullable: false)]
    private $sessionEnchere;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCleConnexion(): ?string
    {
        return $this->cleConnexion;
    }

    public function setFermee(bool $fermee): self
    {
        $this->fermee = $fermee;

        return $this;
    }

    public function getFermee(): ?bool
    {
        return $this->fermee;
    }

    public function setCleConnexion(string $cleConnexion): self
    {
        $this->cleConnexion = $cleConnexion;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getSessionEnchere(): ?SessionEnchere
    {
        return $this->sessionEnchere;
    }

    public function setSessionEnchere(SessionEnchere $sessionEnchere): self
    {
        $this->sessionEnchere = $sessionEnchere;

        return $this;
    }
}
