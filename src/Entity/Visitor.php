<?php

namespace App\Entity;

use App\Repository\VisitorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VisitorRepository::class)
 */
class Visitor
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="visitors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $sex;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateOfVisit;



    /**
     * @ORM\Column(type="string", length=50)
     */
    private $relation;

    /**
     * @ORM\ManyToOne(targetEntity=Admimssion::class, inversedBy="visitors")
     * @ORM\JoinColumn(nullable=true)
     */
    private $admission;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="visitors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $exitTime;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getDateOfVisit(): ?\DateTimeInterface
    {
        return $this->dateOfVisit;
    }

    public function setDateOfVisit(\DateTimeInterface $dateOfVisit): self
    {
        $this->dateOfVisit = $dateOfVisit;

        return $this;
    }



    public function getRelation(): ?string
    {
        return $this->relation;
    }

    public function setRelation(string $relation): self
    {
        $this->relation = $relation;

        return $this;
    }

    public function getAdmission(): ?Admimssion
    {
        return $this->admission;
    }

    public function setAdmission(?Admimssion $admission): self
    {
        $this->admission = $admission;

        return $this;
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

    public function getExitTime(): ?\DateTimeInterface
    {
        return $this->exitTime;
    }

    public function setExitTime(\DateTimeInterface $exitTime): self
    {
        $this->exitTime = $exitTime;

        return $this;
    }
}
