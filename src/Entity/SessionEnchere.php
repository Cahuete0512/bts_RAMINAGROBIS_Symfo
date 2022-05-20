<?php

namespace App\Entity;

use App\Repository\SessionEnchereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

#[ORM\Entity(repositoryClass: SessionEnchereRepository::class)]
class SessionEnchere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $idPanier;

    #[ORM\Column(type: 'integer')]
    private $numeroSemaine;

    #[ORM\Column(type: 'datetime')]
    private $debutEnchere;

    #[ORM\Column(type: 'datetime')]
    private $finEnchere;

    #[ORM\OneToMany(mappedBy: 'sessionEnchere', targetEntity: LignePanier::class, cascade: ['persist'])]
    private $lignesPaniers;

    #[ORM\OneToMany(mappedBy: 'sessionEnchere', targetEntity: SessionEnchereFournisseur::class, cascade: ['persist'])]
    private $sessionEnchereFournisseurs;

    public function __construct()
    {
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
     * @return int|null
     */
    public function getIdPanier(): ?int
    {
        return $this->idPanier;
    }

    /**
     * @param integer $idPanier
     * @return $this
     */
    public function setIdPanier(int $idPanier): self
    {
        $this->idPanier = $idPanier;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumeroSemaine(): ?int
    {
        return $this->numeroSemaine;
    }

    /**
     * @param integer $numeroSemaine
     * @return $this
     */
    public function setNumeroSemaine(int $numeroSemaine): self
    {
        $this->numeroSemaine = $numeroSemaine;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getLignesPaniers(): Collection
    {
        return $this->lignesPaniers;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDebutEnchere(): ?\DateTimeInterface
    {
        return $this->debutEnchere;
    }

    /**
     * @param \DateTimeInterface $debutEnchere
     * @return $this
     */
    public function setDebutEnchere(\DateTimeInterface $debutEnchere): self
    {
        $this->debutEnchere = $debutEnchere;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getFinEnchere(): ?\DateTimeInterface
    {
        return $this->finEnchere;
    }

    /**
     * @param \DateTimeInterface $finEnchere
     * @return $this
     */
    public function setFinEnchere(\DateTimeInterface $finEnchere): self
    {
        $this->finEnchere = $finEnchere;

        return $this;
    }

    /**
     * @param LignePanier $idLignePanier
     * @return $this
     */
    public function addIdLignePanier(LignePanier $idLignePanier): self
    {
        if (!$this->id_ligne_panier->contains($idLignePanier)) {
            $this->id_ligne_panier[] = $idLignePanier;
            $idLignePanier->setSessionEnchere($this);
        }

        return $this;
    }

    /**
     * @param LignePanier $idLignePanier
     * @return $this
     */
    public function removeIdLignePanier(LignePanier $idLignePanier): self
    {
        if ($this->id_ligne_panier->removeElement($idLignePanier)) {
            // set the owning side to null (unless already changed)
            if ($idLignePanier->getSessionEnchere() === $this) {
                $idLignePanier->setSessionEnchere(null);
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
     * @param SessionEnchereFournisseur $sessionEnchereFournisseur
     * @return $this
     */
    public function addSessionEnchereFournisseur(SessionEnchereFournisseur $sessionEnchereFournisseur): self
    {
        if (!$this->sessionEnchereFournisseurs->contains($sessionEnchereFournisseur)) {
            $this->sessionEnchereFournisseurs[] = $sessionEnchereFournisseur;
            $sessionEnchereFournisseur->setSessionEnchere($this);
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
            if ($sessionEnchereFournisseur->getSessionEnchere() === $this) {
                $sessionEnchereFournisseur->setSessionEnchere(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getFournisseurs(): Collection{
        $fournisseurs = new ArrayCollection();
        foreach ($this->sessionEnchereFournisseurs as $sessionEnchereFournisseur){
            $fournisseurs[] = $sessionEnchereFournisseur->getFournisseur();
        }

        return $fournisseurs;
    }
}
