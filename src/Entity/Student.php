<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student
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
    private $studentId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fatherName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $gender;

    /**
     * @ORM\ManyToOne(targetEntity=Woreda::class, inversedBy="students")
     */
    private $woreda;

    /**
     * @ORM\ManyToOne(targetEntity=Program::class, inversedBy="students")
     */
    private $program;

    /**
     * @ORM\ManyToOne(targetEntity=ProgramLevel::class, inversedBy="students")
     */
    private $programLevel;

    /**
     * @ORM\ManyToOne(targetEntity=Modality::class, inversedBy="students")
     */
    private $modality;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $academicYear;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=Nationality::class, inversedBy="students")
     */
    private $nationality;

    /**
     * @ORM\OneToMany(targetEntity=Diploma::class, mappedBy="student")
     */
    private $diplomas;

    /**
     * @ORM\OneToMany(targetEntity=Preparatory::class, mappedBy="student")
     */
    private $preparatories;

    /**
     * @ORM\OneToMany(targetEntity=HightSchool::class, mappedBy="student")
     */
    private $hightSchools;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status;

    public function __construct()
    {
        $this->diplomas = new ArrayCollection();
        $this->preparatories = new ArrayCollection();
        $this->hightSchools = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->firstName. " ".$this->fatherName." ".$this->lastName; 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentId(): ?int
    {
        return $this->studentId;
    }

    public function setStudentId(int $studentId): self
    {
        $this->studentId = $studentId;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFatherName(): ?string
    {
        return $this->fatherName;
    }

    public function setFatherName(string $fatherName): self
    {
        $this->fatherName = $fatherName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

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

    public function getProgram(): ?Program
    {
        return $this->program;
    }

    public function setProgram(?Program $program): self
    {
        $this->program = $program;

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

    public function getModality(): ?Modality
    {
        return $this->modality;
    }

    public function setModality(?Modality $modality): self
    {
        $this->modality = $modality;

        return $this;
    }

    public function getAdmissionDate(): ?\DateTimeInterface
    {
        return $this->admissionDate;
    }

    public function setAdmissionDate(\DateTimeInterface $admissionDate): self
    {
        $this->admissionDate = $admissionDate;

        return $this;
    }

    public function getAcademicYear(): ?string
    {
        return $this->academicYear;
    }

    public function setAcademicYear(string $academicYear): self
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getNationality(): ?Nationality
    {
        return $this->nationality;
    }

    public function setNationality(?Nationality $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * @return Collection<int, Diploma>
     */
    public function getDiplomas(): Collection
    {
        return $this->diplomas;
    }

    public function addDiploma(Diploma $diploma): self
    {
        if (!$this->diplomas->contains($diploma)) {
            $this->diplomas[] = $diploma;
            $diploma->setStudent($this);
        }

        return $this;
    }

    public function removeDiploma(Diploma $diploma): self
    {
        if ($this->diplomas->removeElement($diploma)) {
            // set the owning side to null (unless already changed)
            if ($diploma->getStudent() === $this) {
                $diploma->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Preparatory>
     */
    public function getPreparatories(): Collection
    {
        return $this->preparatories;
    }

    public function addPreparatory(Preparatory $preparatory): self
    {
        if (!$this->preparatories->contains($preparatory)) {
            $this->preparatories[] = $preparatory;
            $preparatory->setStudent($this);
        }

        return $this;
    }

    public function removePreparatory(Preparatory $preparatory): self
    {
        if ($this->preparatories->removeElement($preparatory)) {
            // set the owning side to null (unless already changed)
            if ($preparatory->getStudent() === $this) {
                $preparatory->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HightSchool>
     */
    public function getHightSchools(): Collection
    {
        return $this->hightSchools;
    }

    public function addHightSchool(HightSchool $hightSchool): self
    {
        if (!$this->hightSchools->contains($hightSchool)) {
            $this->hightSchools[] = $hightSchool;
            $hightSchool->setStudent($this);
        }

        return $this;
    }

    public function removeHightSchool(HightSchool $hightSchool): self
    {
        if ($this->hightSchools->removeElement($hightSchool)) {
            // set the owning side to null (unless already changed)
            if ($hightSchool->getStudent() === $this) {
                $hightSchool->setStudent(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
