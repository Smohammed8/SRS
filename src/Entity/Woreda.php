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
     * @ORM\ManyToOne(targetEntity=Zone::class, inversedBy="woredas")
     */
    private $zone;

    /**
     * @ORM\OneToMany(targetEntity=Patient::class, mappedBy="woreda")
     */
    private $patients;

    /**
     * @ORM\OneToMany(targetEntity=Campus::class, mappedBy="woreda", orphanRemoval=true)
     */
    private $campuses;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="woreda")
     */
    private $students;
    
      public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
  
        $this->patients = new ArrayCollection();
        $this->campuses = new ArrayCollection();
        $this->students = new ArrayCollection();
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

    /**
     * @return Collection<int, Campus>
     */
    public function getCampuses(): Collection
    {
        return $this->campuses;
    }

    public function addCampus(Campus $campus): self
    {
        if (!$this->campuses->contains($campus)) {
            $this->campuses[] = $campus;
            $campus->setWoreda($this);
        }

        return $this;
    }

    public function removeCampus(Campus $campus): self
    {
        if ($this->campuses->removeElement($campus)) {
            // set the owning side to null (unless already changed)
            if ($campus->getWoreda() === $this) {
                $campus->setWoreda(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->setWoreda($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getWoreda() === $this) {
                $student->setWoreda(null);
            }
        }

        return $this;
    }
}
