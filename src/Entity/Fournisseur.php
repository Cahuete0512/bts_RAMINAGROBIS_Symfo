<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\ManyToMany(targetEntity: LignePanier::class, inversedBy: 'fournisseurs')]
    #[ORM\JoinColumn(nullable: false)]
    private $lignesPaniers;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: Enchere::class)]
    private $encheres;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: SessionEnchereFournisseur::class)]
    private $sessionEnchereFournisseurs;

    public function __construct()
    {
        $this->encheres = new ArrayCollection();
        $this->lignesPaniers = new ArrayCollection();
        $this->sessionEnchereFournisseurs = new ArrayCollection();
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
     * @return Collection|null
     */
    public function getEncheres(): ?Collection
    {
        return $this->encheres;
    }

    /**
     * @return Collection|null
     */
    public function getLignesPaniers(): ?Collection
    {
        return $this->lignesPaniers;
    }

    /**
     * @param Enchere $enchere
     * @return $this
     */
    public function addEnchere(Enchere $enchere): self
    {
        if (!$this->encheres->contains($enchere)) {
            $this->encheres[] = $enchere;
            $enchere->setFournisseur($this);
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
            if ($enchere->getFournisseur() === $this) {
                $enchere->setFournisseur(null);
            }
        }

        return $this;
    }

    /**
     * @param LignePanier $lignePanier
     * @return $this
     */
    public function addLignePanier(LignePanier $lignePanier): self
    {
        if (!$this->lignesPaniers->contains($lignePanier)) {
            $this->lignesPaniers[] = $lignePanier;
            $lignePanier->setFournisseur($this);
        }

        return $this;
    }

    /**
     * @param LignePanier $lignePanier
     * @return $this
     */
    public function removeLignePanier(LignePanier $lignePanier): self
    {
        if ($this->lignesPaniers->removeElement($lignePanier)) {
            // set the owning side to null (unless already changed)
            if ($lignePanier->getFournisseur() === $this) {
                $lignePanier->setFournisseur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getSessionEnchereFournisseurs(): Collection
    {
        return $this->sessionEnchereFournisseurs;
    }

    /**
     * @param Collection $sessionsEncheresFournisseurs
     * @return $this
     */
    public function setSessionEnchereFournisseurs(Collection $sessionsEncheresFournisseurs): self
    {
        $this->sessionEnchereFournisseurs = $sessionsEncheresFournisseurs;

        return $this;
    }

    /**
     * @param SessionEnchereFournisseur $sessionEnchereFournisseur
     * @return $this
     */
    public function addSessionEnchereFournisseur(SessionEnchereFournisseur $sessionEnchereFournisseur): self
    {
        if (!$this->sessionEnchereFournisseurs->contains($sessionEnchereFournisseur)) {
            $this->sessionEnchereFournisseurs[] = $sessionEnchereFournisseur;
            $sessionEnchereFournisseur->setFournisseur($this);
        }

        return $this;
    }

    /**
     * @param SessionEnchereFournisseur $sessionEnchereFournisseur
     * @return $this
     */
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

    /**
     * récupère la session actuelle du fournisseur
     * @return SessionEnchereFournisseur|null
     */
    public function getSessionEnchereFournisseurActuelle(): ?SessionEnchereFournisseur
    {
        foreach ($this->getSessionEnchereFournisseurs() as $sessionEnchereFournisseur){
            $sessionEnchereFournisseur->getSessionEnchere();
            $now = new \DateTime('now');
            if($now >= $sessionEnchereFournisseur->getSessionEnchere()->getDebutEnchere()
                && $now <= $sessionEnchereFournisseur->getSessionEnchere()->getFinEnchere()){
                return $sessionEnchereFournisseur;
            }
        }
        return null;
    }
}
