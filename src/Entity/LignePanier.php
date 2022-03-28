<?php

namespace App\Entity;

use App\Repository\LignePanierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LignePanierRepository::class)]
class LignePanier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $reference;

    #[ORM\Column(type: 'integer')]
    private $quantite;

    #[ORM\Column(type: 'integer')]
    private $idFournisseur;

    #[ORM\Column(type: 'integer')]
    private $idEnchere;

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
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     * @return $this
     */
    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    /**
     * @param int $quantite
     * @return $this
     */
    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdFournisseur(): ?int
    {
        return $this->idFournisseur;
    }

    /**
     * @param int $idFournisseur
     * @return $this
     */
    public function setIdFournisseur(int $idFournisseur): self
    {
        $this->idFournisseur = $idFournisseur;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdEnchere(): ?int
    {
        return $this->idEnchere;
    }

    /**
     * @param int $idEnchere
     * @return $this
     */
    public function setIdEnchere(int $idEnchere): self
    {
        $this->idEnchere = $idEnchere;

        return $this;
    }
}
