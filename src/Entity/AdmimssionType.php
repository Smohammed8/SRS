<?php

namespace App\Entity;

use App\Repository\AdmimssionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdmimssionTypeRepository::class)
 */
class AdmimssionType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Admimssion::class, mappedBy="type", orphanRemoval=true)
     */
    private $admimssions;
    
    public function __toString()
    {
   return $this->name;     
    
   
    }

    public function __construct()
    {
        $this->admimssions = new ArrayCollection();
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
     * @return Collection<int, Admimssion>
     */
    public function getAdmimssions(): Collection
    {
        return $this->admimssions;
    }

    public function addAdmimssion(Admimssion $admimssion): self
    {
        if (!$this->admimssions->contains($admimssion)) {
            $this->admimssions[] = $admimssion;
            $admimssion->setType($this);
        }

        return $this;
    }

    public function removeAdmimssion(Admimssion $admimssion): self
    {
        if ($this->admimssions->removeElement($admimssion)) {
            // set the owning side to null (unless already changed)
            if ($admimssion->getType() === $this) {
                $admimssion->setType(null);
            }
        }

        return $this;
    }
}
