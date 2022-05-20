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

    #[ORM\ManyToOne(targetEntity: SessionEnchere::class, inversedBy: 'lignesPaniers')]
    #[ORM\JoinColumn(nullable: false)]
    private $sessionEnchere;

    #[ORM\OneToMany(mappedBy: 'lignePanier', targetEntity: Enchere::class, fetch: "EAGER")]
    private $encheres;

    #[ORM\ManyToMany(targetEntity: Fournisseur::class, mappedBy: 'lignesPaniers', cascade: ['persist'])]
    private $fournisseurs;

    private $statutPrix;

    /**
     * @return string
     */
    public function getStatutPrix(): string
    {
        return $this->statutPrix;
    }

    /**
     * @param $statutPrix
     * @return $this
     */
    public function setStatutPrix($statutPrix): self
    {
        $this->statutPrix = $statutPrix;

        return $this;
    }

    public function __construct()
    {
        $this->encheres = new ArrayCollection();
        $this->fournisseurs = new ArrayCollection();
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
     * @return Collection|null
     */
    public function getFournisseurs(): ?Collection
    {
        return $this->fournisseurs;
    }

    /**
     * @return Collection|null
     */
    public function getEncheres(): ?Collection
    {
        return $this->encheres;
    }

    /**
     * @return SessionEnchere|null
     */
    public function getSessionEnchere(): ?SessionEnchere
    {
        return $this->sessionEnchere;
    }

    /**
     * @param SessionEnchere|null $sessionEnchere
     * @return $this
     */
    public function setSessionEnchere(?SessionEnchere $sessionEnchere): self
    {
        $this->sessionEnchere = $sessionEnchere;

        return $this;
    }

    /**
     * @param Enchere $enchere
     * @return $this
     */
    public function addEnchere(Enchere $enchere): self
    {
        if (!$this->encheres->contains($enchere)) {
            $this->encheres[] = $enchere;
            $enchere->setLignePanier($this);
        }

        return $this;
    }

    /**
     * @param Enchere $enchere
     * @return $this
     */
    public function removeEnchere(Enchere $enchere): self
    {
        if ($this->encheres->removeElement($enchere)) {
            // set the owning side to null (unless already changed)
            if ($enchere->getLignePanier() === $this) {
                $enchere->setLignePanier(null);
            }
        }

        return $this;
    }

    /**
     * @param Fournisseur $fournisseur
     * @return $this
     */
    public function addFournisseur(Fournisseur $fournisseur): self
    {
        if (!$this->fournisseurs->contains($fournisseur)) {
            $this->fournisseurs[] = $fournisseur;
            $fournisseur->setLignePanier($this);
        }

        return $this;
    }

    /**
     * @param Fournisseur $fournisseur
     * @return $this
     */
    public function removeFournisseur(Fournisseur $fournisseur): self
    {
        if ($this->fournisseurs->removeElement($fournisseur)) {
            // set the owning side to null (unless already changed)
            if ($fournisseur->getLignePanier() === $this) {
                $fournisseur->setLignePanier(null);
            }
        }

        return $this;
    }
}
