<?php

namespace App\Entity;

use App\Repository\ProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProgramRepository::class)
 */
class Program
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
     * @ORM\OneToMany(targetEntity=ProgramLevel::class, mappedBy="program", orphanRemoval=true)
     */
    private $programLevels;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="program")
     */
    private $students;


    /**
     * @ORM\Column(type="integer")
     */
    private $totalSemester;

    /**
     * @ORM\OneToMany(targetEntity=Course::class, mappedBy="program")
     */
    private $courses;

    /**
     * @ORM\OneToMany(targetEntity=Slip::class, mappedBy="program")
     */
    private $slips;

    /**
     * @ORM\ManyToOne(targetEntity=ProgramLevel::class, inversedBy="programs")
     */
    private $programLevel;

    public function __construct()
    {
        $this->programLevels = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->courses = new ArrayCollection();
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

    // /**
    //  * @return Collection<int, ProgramLevel>
    //  */
    // public function getProgramLevels(): Collection
    // {
    //     return $this->programLevels;
    // }

    // public function addProgramLevel(ProgramLevel $programLevel): self
    // {
    //     if (!$this->programLevels->contains($programLevel)) {
    //         $this->programLevels[] = $programLevel;
    //         $programLevel->setProgram($this);
    //     }

    //     return $this;
    // }

    // public function removeProgramLevel(ProgramLevel $programLevel): self
    // {
    //     if ($this->programLevels->removeElement($programLevel)) {
    //         // set the owning side to null (unless already changed)
    //         if ($programLevel->getProgram() === $this) {
    //             $programLevel->setProgram(null);
    //         }
    //     }

    //     return $this;
    // }

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
            $student->setProgram($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getProgram() === $this) {
                $student->setProgram(null);
            }
        }

        return $this;
    }



    public function getTotalSemester(): ?int
    {
        return $this->totalSemester;
    }

    public function setTotalSemester(int $totalSemester): self
    {
        $this->totalSemester = $totalSemester;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->setProgram($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getProgram() === $this) {
                $course->setProgram(null);
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
            $slip->setProgram($this);
        }

        return $this;
    }

    public function removeSlip(Slip $slip): self
    {
        if ($this->slips->removeElement($slip)) {
            // set the owning side to null (unless already changed)
            if ($slip->getProgram() === $this) {
                $slip->setProgram(null);
            }
        }

        return $this;
    }

    public function getProgramLevel(): ?ProgramLevel
    {
        return $this->programLevel;
    }

    public function setProgramLevel(?ProgramLevel $programLevel): self
    {
        $this->programLevel = $programLevel;

        return $this;
    }
}
