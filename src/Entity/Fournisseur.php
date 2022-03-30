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

    #[ORM\Column(type: 'string', length: 4)]
    private $civiliteContact;

    #[ORM\Column(type: 'string', length: 255)]
    private $nomContact;

    #[ORM\Column(type: 'string', length: 255)]
    private $prenomContact;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $adresse;

    #[ORM\Column(type: 'string', length: 255)]
    private $numeroSession;

    #[ORM\Column(type: 'integer')]
    private $idEnchere;

    #[ORM\ManyToOne(targetEntity: LignePanier::class, inversedBy: 'id_fournisseur')]
    #[ORM\JoinColumn(nullable: false)]
    private $lignePanier;

    #[ORM\OneToMany(mappedBy: 'fournisseur', targetEntity: Enchere::class)]
    private $id_enchere;

    public function __construct()
    {
        $this->id_enchere = new ArrayCollection();
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
    public function getCiviliteContact(): ?string
    {
        return $this->civiliteContact;
    }

    /**
     * @param $civiliteContact
     * @return $this
     */
    public function setCiviliteContact($civiliteContact): self
    {
        $this->civiliteContact = $civiliteContact;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNomContact(): ?string
    {
        return $this->nomContact;
    }

    /**
     * @param $nomContact
     * @return $this
     */
    public function setNomContact($nomContact): self
    {
        $this->nomContact = $nomContact;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrenomContact(): ?string
    {
        return $this->prenomContact;
    }

    /**
     * @param $prenomContact
     * @return $this
     */
    public function setPrenomContact($prenomContact): self
    {
        $this->prenomContact = $prenomContact;

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
     * @return mixed
     */
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     * @return $this
     */
    public function setAdresse($adresse): self
    {
        $this->adresse = $adresse;

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
     * @return int|null
     */
    public function getIdEnchere(): ?int
    {
        return $this->idEnchere;
    }

    /**
     * @param string $idEnchere
     * @return $this
     */
    public function setIdEnchere(string $idEnchere): self
    {
        $this->idEnchere = $idEnchere;

        return $this;
    }

    public function getLignePanier(): ?LignePanier
    {
        return $this->lignePanier;
    }

    public function setLignePanier(?LignePanier $lignePanier): self
    {
        $this->lignePanier = $lignePanier;

        return $this;
    }

    public function addIdEnchere(Enchere $idEnchere): self
    {
        if (!$this->id_enchere->contains($idEnchere)) {
            $this->id_enchere[] = $idEnchere;
            $idEnchere->setFournisseur($this);
        }

        return $this;
    }

    public function removeIdEnchere(Enchere $idEnchere): self
    {
        if ($this->id_enchere->removeElement($idEnchere)) {
            // set the owning side to null (unless already changed)
            if ($idEnchere->getFournisseur() === $this) {
                $idEnchere->setFournisseur(null);
            }
        }

        return $this;
    }
}
