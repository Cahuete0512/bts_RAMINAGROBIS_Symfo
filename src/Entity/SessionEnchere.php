<?php

namespace App\Entity;

use App\Repository\SessionEnchereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionEnchereRepository::class)]
class SessionEnchere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $idLignePanier;

    #[ORM\Column(type: 'datetime')]
    private $debutEnchere;

    #[ORM\Column(type: 'datetime')]
    private $finEnchere;

    #[ORM\OneToMany(mappedBy: 'sessionEnchere', targetEntity: LignePanier::class)]
    private $id_ligne_panier;

    public function __construct()
    {
        $this->id_ligne_panier = new ArrayCollection();
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
    public function getIdLignePanier(): ?int
    {
        return $this->idLignePanier;
    }

    /**
     * @param int $idLignePanier
     * @return $this
     */
    public function setIdPanier(int $idLignePanier): self
    {
        $this->idLignePanier = $idLignePanier;

        return $this;
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
}
