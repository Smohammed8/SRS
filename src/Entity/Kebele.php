<?php

namespace App\Entity;

use App\Repository\KebeleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=KebeleRepository::class)
 */
class Kebele
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
     * @ORM\ManyToOne(targetEntity=Woreda::class, inversedBy="kebeles")
     */
    private $woreda;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $discription;

    /**
     * @ORM\OneToMany(targetEntity=Patient::class, mappedBy="kebele")
     */
    private $patients;
    
      public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
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

    public function getWoreda(): ?Woreda
    {
        return $this->woreda;
    }

    public function setWoreda(?Woreda $woreda): self
    {
        $this->woreda = $woreda;

        return $this;
    }

    public function getDiscription(): ?string
    {
        return $this->discription;
    }

    public function setDiscription(?string $discription): self
    {
        $this->discription = $discription;

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
            $patient->setKebele($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getKebele() === $this) {
                $patient->setKebele(null);
            }
        }

        return $this;
    }
}
