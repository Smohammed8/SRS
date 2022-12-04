<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
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
     * @ORM\Column(type="string", length=255)
     */
    private $ccode;

    /**
     * @ORM\Column(type="integer")
     */
    private $creditHour;

    /**
     * @ORM\Column(type="integer")
     */
    private $ects;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="courses")
     */
    private $prerequisite;

    /**
     * @ORM\OneToMany(targetEntity=Course::class, mappedBy="prerequisite")
     */
    private $courses;

    /**
     * @ORM\OneToMany(targetEntity=Slip::class, mappedBy="course", orphanRemoval=true)
     */
    private $slips;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="courses")
     * @ORM\JoinColumn(nullable=true)
     */
    private $department;

    /**
     * @ORM\ManyToOne(targetEntity=Year::class, inversedBy="courses")
     * @ORM\JoinColumn(nullable=true)
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity=Semester::class, inversedBy="courses")
     * @ORM\JoinColumn(nullable=true)
     */
    private $semester;

    /**
     * @ORM\ManyToOne(targetEntity=CourseType::class, inversedBy="courses")
     */
    private $type;



    public function __construct()
    {
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

    public function getCcode(): ?string
    {
        return $this->ccode;
    }

    public function setCcode(string $ccode): self
    {
        $this->ccode = $ccode;

        return $this;
    }

    public function getCreditHour(): ?int
    {
        return $this->creditHour;
    }

    public function setCreditHour(int $creditHour): self
    {
        $this->creditHour = $creditHour;

        return $this;
    }

    public function getEcts(): ?int
    {
        return $this->ects;
    }

    public function setEcts(int $ects): self
    {
        $this->ects = $ects;

        return $this;
    }

    public function getPrerequisite(): ?self
    {
        return $this->prerequisite;
    }

    public function setPrerequisite(?self $prerequisite): self
    {
        $this->prerequisite = $prerequisite;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(self $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->setPrerequisite($this);
        }

        return $this;
    }

    public function removeCourse(self $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getPrerequisite() === $this) {
                $course->setPrerequisite(null);
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
            $slip->setCourse($this);
        }

        return $this;
    }

    public function removeSlip(Slip $slip): self
    {
        if ($this->slips->removeElement($slip)) {
            // set the owning side to null (unless already changed)
            if ($slip->getCourse() === $this) {
                $slip->setCourse(null);
            }
        }

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

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

    public function getSemester(): ?Semester
    {
        return $this->semester;
    }

    public function setSemester(?Semester $semester): self
    {
        $this->semester = $semester;

        return $this;
    }

    public function getType(): ?CourseType
    {
        return $this->type;
    }

    public function setType(?CourseType $type): self
    {
        $this->type = $type;

        return $this;
    }

 
}
