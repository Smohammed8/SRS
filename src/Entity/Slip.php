<?php

namespace App\Entity;

use App\Repository\SlipRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SlipRepository::class)
 */
class Slip
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

 
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="createdBy")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Semester::class, inversedBy="slips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $semester;

    /**
     * @ORM\ManyToOne(targetEntity=Modality::class, inversedBy="slips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $modality;

    /**
     * @ORM\ManyToOne(targetEntity=Year::class, inversedBy="slips")
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="slips")
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity=Program::class, inversedBy="slips")
     */
    private $program;

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }



    public function getSemester(): ?Semester
    {
        return $this->semester;
    }

    public function setSemester(?Semester $semester): self
    {
        $this->semester = $semester;

        return $this;
    }

    public function getModality(): ?Modality
    {
        return $this->modality;
    }

    public function setModality(?Modality $modality): self
    {
        $this->modality = $modality;

        return $this;
    }

    public function getYear(): ?Year
    {
        return $this->year;
    }

    public function setYear(?Year $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getProgram(): ?Program
    {
        return $this->program;
    }

    public function setProgram(?Program $program): self
    {
        $this->program = $program;

        return $this;
    }
}
