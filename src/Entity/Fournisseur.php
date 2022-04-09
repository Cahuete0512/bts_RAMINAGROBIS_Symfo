<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
class Fournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $societe;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $numeroSession;

    #[ORM\ManyToMany(targetEntity: LignePanier::class, inversedBy: 'fournisseurs')]
    #[ORM\JoinColumn(nullable: false)]
    private $lignePaniers;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: Enchere::class)]
    private $encheres;

    #[ORM\ManyToOne(targetEntity: SessionEnchereFournisseur::class, inversedBy: 'fournisseur')]
    private $sessionEnchereFournisseurs;

    public function __construct()
    {
        $this->encheres = new ArrayCollection();
        $this->lignePaniers = new ArrayCollection();
        $this->sessionEnchereFournisseurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getSociete(): ?string
    {
        return $this->societe;
    }

    /**
     * @param string $societe
     * @return $this
     */
    public function setSociete(string $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }
    /**
     * @return int|null
     */
    public function getNumeroSession(): ?int
    {
        return $this->numeroSession;
    }

    /**
     * @param mixed $numeroSession
     * @return $this
     */
    public function setNumeroSession($numeroSession): self
    {
        $this->numeroSession = $numeroSession;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getEncheres(): ?Collection
    {
        return $this->encheres;
    }

    public function getLignePaniers(): ?Collection
    {
        return $this->lignePaniers;
    }

    public function addEnchere(Enchere $enchere): self
    {
        if (!$this->encheres->contains($enchere)) {
            $this->encheres[] = $enchere;
            $enchere->setFournisseur($this);
        }

        return $this;
    }

    public function removeEnchere(Enchere $enchere): self
    {
        if ($this->encheres->removeElement($enchere)) {
            // set the owning side to null (unless already changed)
            if ($enchere->getFournisseur() === $this) {
                $enchere->setFournisseur(null);
            }
        }

        return $this;
    }

    public function addLignePanier(LignePanier $lignePanier): self
    {
        if (!$this->lignePaniers->contains($lignePanier)) {
            $this->lignePaniers[] = $lignePanier;
            $lignePanier->setFournisseur($this);
        }

        return $this;
    }

    public function removeLignePanier(LignePanier $lignePanier): self
    {
        if ($this->lignePaniers->removeElement($lignePanier)) {
            // set the owning side to null (unless already changed)
            if ($lignePanier->getFournisseur() === $this) {
                $lignePanier->setFournisseur(null);
            }
        }

        return $this;
    }

    public function addSessionEnchereFournisseur(SessionEnchereFournisseur $sessionEnchereFournisseur): self
    {
        if (!$this->sessionEnchereFournisseurs->contains($sessionEnchereFournisseur)) {
            $this->sessionEnchereFournisseurs[] = $sessionEnchereFournisseur;
            $sessionEnchereFournisseur->setFournisseur($this);
        }

        return $this;
    }

    public function removeSessionEnchereFournisseur(SessionEnchereFournisseur $sessionEnchereFournisseur): self
    {
        if ($this->sessionEnchereFournisseurs->removeElement($sessionEnchereFournisseur)) {
            // set the owning side to null (unless already changed)
            if ($sessionEnchereFournisseur->getFournisseur() === $this) {
                $sessionEnchereFournisseur->setFournisseur(null);
            }
        }

        return $this;
    }
}
