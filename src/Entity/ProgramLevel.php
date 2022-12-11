<?php

namespace App\Entity;

use App\Repository\ProgramLevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProgramLevelRepository::class)
 */
class ProgramLevel
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

    // /**
    //  * @ORM\ManyToOne(targetEntity=Program::class, inversedBy="programLevels")
    //  * @ORM\JoinColumn(nullable=false)
    //  */
    // private $program;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="programLevel")
     */
    private $students;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="programLevels")
     */
    private $department;

    /**
     * @ORM\OneToMany(targetEntity=Program::class, mappedBy="programLevel")
     */
    private $programs;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->programs = new ArrayCollection();
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

    // public function getProgram(): ?Program
    // {
    //     return $this->program;
    // }

    // public function setProgram(?Program $program): self
    // {
    //     $this->program = $program;

    //     return $this;
    // }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    // public function addStudent(Student $student): self
    // {
    //     if (!$this->students->contains($student)) {
    //         $this->students[] = $student;
    //         $student->setProgramLevel($this);
    //     }

    //     return $this;
    // }

    // public function removeStudent(Student $student): self
    // {
    //     if ($this->students->removeElement($student)) {
    //         // set the owning side to null (unless already changed)
    //         if ($student->getProgramLevel() === $this) {
    //             $student->setProgramLevel(null);
    //         }
    //     }

    //     return $this;
    // }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection<int, Program>
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Program $program): self
    {
        if (!$this->programs->contains($program)) {
            $this->programs[] = $program;
            $program->setProgramLevel($this);
        }

        return $this;
    }

    public function removeProgram(Program $program): self
    {
        if ($this->programs->removeElement($program)) {
            // set the owning side to null (unless already changed)
            if ($program->getProgramLevel() === $this) {
                $program->setProgramLevel(null);
            }
        }

        return $this;
    }
}
