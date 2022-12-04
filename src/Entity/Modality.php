<?php

namespace App\Entity;

use App\Repository\ModalityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModalityRepository::class)
 */
class Modality
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="modality")
     */
    private $students;

    /**
     * @ORM\OneToMany(targetEntity=Slip::class, mappedBy="modality", orphanRemoval=true)
     */
    private $slips;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->slips = new ArrayCollection();
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
            $student->setModality($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getModality() === $this) {
                $student->setModality(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Slip>
     */
    public function getSlips(): Collection
    {
        return $this->slips;
    }

    public function addSlip(Slip $slip): self
    {
        if (!$this->slips->contains($slip)) {
            $this->slips[] = $slip;
            $slip->setModality($this);
        }

        return $this;
    }

    public function removeSlip(Slip $slip): self
    {
        if ($this->slips->removeElement($slip)) {
            // set the owning side to null (unless already changed)
            if ($slip->getModality() === $this) {
                $slip->setModality(null);
            }
        }

        return $this;
    }
}
