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

    #[ORM\Column(type: 'datetime')]
    private $debutEnchere;

    #[ORM\Column(type: 'datetime')]
    private $finEnchere;

    #[ORM\OneToMany(mappedBy: 'sessionEnchere', targetEntity: LignePanier::class, cascade: ['persist'])]
    private $lignePaniers;

    #[ORM\ManyToOne(targetEntity: SessionEnchereFournisseur::class, inversedBy: 'sessionEnchere')]
    private $sessionEnchereFournisseurs;

    public function __construct()
    {
        $this->lignePaniers = new ArrayCollection();
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
     * @return
     */
    public function getLignePaniers(): Collection
    {
        return $this->lignePaniers;
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

    public function addIdLignePanier(LignePanier $idLignePanier): self
    {
        if (!$this->id_ligne_panier->contains($idLignePanier)) {
            $this->id_ligne_panier[] = $idLignePanier;
            $idLignePanier->setSessionEnchere($this);
        }

        return $this;
    }

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

    public function addSessionEnchereFournisseur(SessionEnchereFournisseur $sessionEnchereFournisseur): self
    {
        if (!$this->sessionEnchereFournisseurs->contains($sessionEnchereFournisseur)) {
            $this->sessionEnchereFournisseurs[] = $sessionEnchereFournisseur;
            $sessionEnchereFournisseur->setSessionEnchere($this);
        }

        return $this;
    }

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
}
