<?php

namespace App\Entity;

use App\Repository\OutcomeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OutcomeRepository::class)
 */
class Outcome
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    

    /**
     * @ORM\OneToMany(targetEntity=Admimssion::class, mappedBy="outcome", orphanRemoval=true)
     */
    private $admimssions;

    public function __construct()
    {
        $this->admimssions = new ArrayCollection();
    }
    public function __toString()
    {
   return $this->name;     
    
   
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Admimssion[]
     */
    public function getAdmimssions(): Collection
    {
        return $this->admimssions;
    }

    public function addAdmimssion(Admimssion $admimssion): self
    {
        if (!$this->admimssions->contains($admimssion)) {
            $this->admimssions[] = $admimssion;
            $admimssion->setOutcome($this);
        }

        return $this;
    }

    public function removeAdmimssion(Admimssion $admimssion): self
    {
        if ($this->admimssions->removeElement($admimssion)) {
            // set the owning side to null (unless already changed)
            if ($admimssion->getOutcome() === $this) {
                $admimssion->setOutcome(null);
            }
        }

        return $this;
    }
}
