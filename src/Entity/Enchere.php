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

    #[ORM\Column(type: 'integer')]
    private $numeroPanier;

    #[ORM\Column(type: 'string', length: 255)]
    private $refProduit;

    #[ORM\Column(type: 'integer')]
    private $idFournisseur;

    #[ORM\Column(type: 'datetime')]
    private $dateEnchere;

    #[ORM\Column(type: 'float')]
    private $prixEnchere;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroPanier(): ?int
    {
        return $this->numeroPanier;
    }

    public function setNumeroPanier(int $numeroPanier): self
    {
        $this->numeroPanier = $numeroPanier;

        return $this;
    }

    public function getRefProduit(): ?string
    {
        return $this->refProduit;
    }

    public function setRefProduit(string $refProduit): self
    {
        $this->refProduit = $refProduit;

        return $this;
    }

    public function getIdFournisseur(): ?int
    {
        return $this->idFournisseur;
    }

    public function setIdFournisseur(int $idFournisseur): self
    {
        $this->idFournisseur = $idFournisseur;

        return $this;
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
}
