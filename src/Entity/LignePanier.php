<?php

namespace App\Entity;

use App\Repository\LignePanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToOne(targetEntity: SessionEnchere::class, inversedBy: 'id_ligne_panier')]
    #[ORM\JoinColumn(nullable: false)]
    private $sessionEnchere;

    #[ORM\OneToMany(mappedBy: 'lignePanier', targetEntity: Enchere::class)]
    private $id_enchere;

    #[ORM\OneToMany(mappedBy: 'lignePanier', targetEntity: Fournisseur::class)]
    private $id_fournisseur;

    public function __construct()
    {
        $this->id_enchere = new ArrayCollection();
        $this->id_fournisseur = new ArrayCollection();
    }

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

    public function getSessionEnchere(): ?SessionEnchere
    {
        return $this->sessionEnchere;
    }

    public function setSessionEnchere(?SessionEnchere $sessionEnchere): self
    {
        $this->sessionEnchere = $sessionEnchere;

        return $this;
    }

    public function addIdEnchere(Enchere $idEnchere): self
    {
        if (!$this->id_enchere->contains($idEnchere)) {
            $this->id_enchere[] = $idEnchere;
            $idEnchere->setLignePanier($this);
        }

        return $this;
    }

    public function removeIdEnchere(Enchere $idEnchere): self
    {
        if ($this->id_enchere->removeElement($idEnchere)) {
            // set the owning side to null (unless already changed)
            if ($idEnchere->getLignePanier() === $this) {
                $idEnchere->setLignePanier(null);
            }
        }

        return $this;
    }

    public function addIdFournisseur(Fournisseur $idFournisseur): self
    {
        if (!$this->id_fournisseur->contains($idFournisseur)) {
            $this->id_fournisseur[] = $idFournisseur;
            $idFournisseur->setLignePanier($this);
        }

        return $this;
    }

    public function removeIdFournisseur(Fournisseur $idFournisseur): self
    {
        if ($this->id_fournisseur->removeElement($idFournisseur)) {
            // set the owning side to null (unless already changed)
            if ($idFournisseur->getLignePanier() === $this) {
                $idFournisseur->setLignePanier(null);
            }
        }

        return $this;
    }
}
