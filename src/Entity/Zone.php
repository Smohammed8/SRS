<?php

namespace App\Entity;

use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZoneRepository::class)
 */
class Zone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="zones")
     */
    private $region;

    /**
     * @ORM\OneToMany(targetEntity=Woreda::class, mappedBy="zone")
     */
    private $woredas;

    /**
     * @ORM\OneToMany(targetEntity=Patient::class, mappedBy="zone")
     */
    private $patients;

    public function __construct()
    {
        $this->woredas = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection<int, Woreda>
     */
    public function getWoredas(): Collection
    {
        return $this->woredas;
    }

    public function addWoreda(Woreda $woreda): self
    {
        if (!$this->woredas->contains($woreda)) {
            $this->woredas[] = $woreda;
            $woreda->setZone($this);
        }

        return $this;
    }

    public function removeWoreda(Woreda $woreda): self
    {
        if ($this->woredas->removeElement($woreda)) {
            // set the owning side to null (unless already changed)
            if ($woreda->getZone() === $this) {
                $woreda->setZone(null);
            }
        }

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
            $patient->setZone($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getZone() === $this) {
                $patient->setZone(null);
            }
        }

        return $this;
    }
}
