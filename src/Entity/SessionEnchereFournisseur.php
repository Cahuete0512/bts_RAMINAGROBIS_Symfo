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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCleConnexion(): ?string
    {
        return $this->cleConnexion;
    }

    /**
     * @param bool $fermee
     * @return $this
     */
    public function setFermee(bool $fermee): self
    {
        $this->fermee = $fermee;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getFermee(): ?bool
    {
        return $this->fermee;
    }

    /**
     * @param string $cleConnexion
     * @return $this
     */
    public function setCleConnexion(string $cleConnexion): self
    {
        $this->cleConnexion = $cleConnexion;

        return $this;
    }

    /**
     * @return Fournisseur|null
     */
    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    /**
     * @param Fournisseur $fournisseur
     * @return $this
     */
    public function setFournisseur(Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    /**
     * @return SessionEnchere|null
     */
    public function getSessionEnchere(): ?SessionEnchere
    {
        return $this->sessionEnchere;
    }

    /**
     * @param SessionEnchere $sessionEnchere
     * @return $this
     */
    public function setSessionEnchere(SessionEnchere $sessionEnchere): self
    {
        $this->sessionEnchere = $sessionEnchere;

        return $this;
    }
}
