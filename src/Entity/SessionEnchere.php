<?php

namespace App\Entity;

use App\Repository\SessionEnchereRepository;
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
}
