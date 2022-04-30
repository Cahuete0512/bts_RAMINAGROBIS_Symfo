<?php

namespace App\Entity;

use App\Repository\EnchereRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnchereRepository::class)]
class Enchere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $dateEnchere;

    #[ORM\Column(type: 'float')]
    private $prixEnchere;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $position;

    #[ORM\ManyToOne(targetEntity: LignePanier::class, inversedBy: 'encheres')]
    #[ORM\JoinColumn(nullable: false)]
    private $lignePanier;

    #[ORM\ManyToOne(targetEntity: Fournisseur::class, inversedBy: 'encheres')]
    #[ORM\JoinColumn(nullable: false)]
    private $fournisseur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEnchere(): ?\DateTimeInterface
    {
        return $this->dateEnchere;
    }

    public function setDateEnchere(\DateTimeInterface $dateEnchere): self
    {
        $this->dateEnchere = $dateEnchere;

        return $this;
    }

    public function getPrixEnchere(): ?float
    {
        return $this->prixEnchere;
    }

    public function setPrixEnchere(float $prixEnchere): self
    {
        $this->prixEnchere = $prixEnchere;

        return $this;
    }

    public function getLignePanier(): ?LignePanier
    {
        return $this->lignePanier;
    }

    public function setLignePanier(?LignePanier $lignePanier): self
    {
        $this->lignePanier = $lignePanier;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(float $position): self
    {
        $this->position = $position;

        return $this;
    }
}
