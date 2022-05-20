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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateEnchere(): ?\DateTimeInterface
    {
        return $this->dateEnchere;
    }

    /**
     * @param \DateTimeInterface $dateEnchere
     * @return $this
     */
    public function setDateEnchere(\DateTimeInterface $dateEnchere): self
    {
        $this->dateEnchere = $dateEnchere;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrixEnchere(): ?float
    {
        return $this->prixEnchere;
    }

    /**
     * @param float $prixEnchere
     * @return $this
     */
    public function setPrixEnchere(float $prixEnchere): self
    {
        $this->prixEnchere = $prixEnchere;

        return $this;
    }

    /**
     * @return LignePanier|null
     */
    public function getLignePanier(): ?LignePanier
    {
        return $this->lignePanier;
    }

    /**
     * @param LignePanier|null $lignePanier
     * @return $this
     */
    public function setLignePanier(?LignePanier $lignePanier): self
    {
        $this->lignePanier = $lignePanier;

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
     * @param Fournisseur|null $fournisseur
     * @return $this
     */
    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param float $position
     * @return $this
     */
    public function setPosition(float $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCouleur(): ?string{
        if($this->position == -1){
            return "rouge";
        }elseif($this->position == 1){
            return "vert";
        }else{
            return "orange";
        }
    }
}
