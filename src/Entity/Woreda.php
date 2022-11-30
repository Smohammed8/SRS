<?php

namespace App\Entity;

use App\Repository\WoredaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WoredaRepository::class)
 */
class Woreda
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Kebele::class, mappedBy="woreda")
     */
    private $kebeles;

    /**
     * @ORM\OneToMany(targetEntity=HealthFacility::class, mappedBy="woreda")
     */
    private $healthFacilities;

    /**
     * @ORM\ManyToOne(targetEntity=Zone::class, inversedBy="woredas")
     */
    private $zone;

    /**
     * @ORM\OneToMany(targetEntity=Patient::class, mappedBy="woreda")
     */
    private $patients;
    
      public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->kebeles = new ArrayCollection();
        $this->healthFacilities = new ArrayCollection();
        $this->patients = new ArrayCollection();
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
     * @return Collection|Kebele[]
     */
    public function getKebeles(): Collection
    {
        return $this->kebeles;
    }

    public function addKebele(Kebele $kebele): self
    {
        if (!$this->kebeles->contains($kebele)) {
            $this->kebeles[] = $kebele;
            $kebele->setWoreda($this);
        }

        return $this;
    }

    public function removeKebele(Kebele $kebele): self
    {
        if ($this->kebeles->removeElement($kebele)) {
            // set the owning side to null (unless already changed)
            if ($kebele->getWoreda() === $this) {
                $kebele->setWoreda(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HealthFacility[]
     */
    public function getHealthFacilities(): Collection
    {
        return $this->healthFacilities;
    }

    public function addHealthFacility(HealthFacility $healthFacility): self
    {
        if (!$this->healthFacilities->contains($healthFacility)) {
            $this->healthFacilities[] = $healthFacility;
            $healthFacility->setWoreda($this);
        }

        return $this;
    }

    public function removeHealthFacility(HealthFacility $healthFacility): self
    {
        if ($this->healthFacilities->removeElement($healthFacility)) {
            // set the owning side to null (unless already changed)
            if ($healthFacility->getWoreda() === $this) {
                $healthFacility->setWoreda(null);
            }
        }

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * @return Collection<int, Patient>
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients[] = $patient;
            $patient->setWoreda($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getWoreda() === $this) {
                $patient->setWoreda(null);
            }
        }

        return $this;
    }
}
