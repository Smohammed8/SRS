<?php

namespace App\Entity;

use App\Repository\DiplomaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiplomaRepository::class)
 */
class Diploma
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $startYear;

    /**
     * @ORM\Column(type="integer")
     */
    private $endyear;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $result;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $collegeName;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $fieldOfStudy;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="diplomas")
     */
    private $student;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartYear(): ?int
    {
        return $this->startYear;
    }

    public function setStartYear(int $startYear): self
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getEndyear(): ?int
    {
        return $this->endyear;
    }

    public function setEndyear(int $endyear): self
    {
        $this->endyear = $endyear;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(string $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getCollegeName(): ?string
    {
        return $this->collegeName;
    }

    public function setCollegeName(string $collegeName): self
    {
        $this->collegeName = $collegeName;

        return $this;
    }

    public function getFieldOfStudy(): ?string
    {
        return $this->fieldOfStudy;
    }

    public function setFieldOfStudy(string $fieldOfStudy): self
    {
        $this->fieldOfStudy = $fieldOfStudy;

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
}
