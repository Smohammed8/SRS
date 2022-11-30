<?php

namespace App\Entity;

use App\Repository\HealthFacilityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HealthFacilityRepository::class)
 */
class HealthFacility
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
     * @ORM\ManyToOne(targetEntity=Woreda::class, inversedBy="healthFacilities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $woreda;

    /**
     * @ORM\OneToMany(targetEntity=Patient::class, mappedBy="referredFrom", orphanRemoval=true)
     */
    private $patients;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $ownership;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $type;

    public function __construct()
    {
        $this->patients = new ArrayCollection();
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



    public function getWoreda(): ?Woreda
    {
        return $this->woreda;
    }

    public function setWoreda(?Woreda $woreda): self
    {
        $this->woreda = $woreda;

        return $this;
    }

    /**
     * @return Collection|Patient[]
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients[] = $patient;
            $patient->setReferredFrom($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getReferredFrom() === $this) {
                $patient->setReferredFrom(null);
            }
        }

        return $this;
    }

    public function getOwnership(): ?string
    {
        return $this->ownership;
    }

    public function setOwnership(string $ownership): self
    {
        $this->ownership = $ownership;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
