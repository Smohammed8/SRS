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
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="slips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @ORM\Column(type="datetime")
     */
    private $seenDate;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $diagnosis;

    /**
     * @ORM\Column(type="string", length=40, nullable=false)
     */
    private $admittingTeam;

    /**
     * @ORM\ManyToOne(targetEntity=Priority::class, inversedBy="slips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $priority;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $evaluatingResident;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $seniorSurgeon;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="slips")
     */
    private $approvedBy;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="createdBy")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSeenDate(): ?\DateTimeInterface
    {
        return $this->seenDate;
    }

    public function setSeenDate(\DateTimeInterface $seenDate): self
    {
        $this->seenDate = $seenDate;

        return $this;
    }

    public function getDiagnosis(): ?string
    {
        return $this->diagnosis;
    }

    public function setDiagnosis(?string $diagnosis): self
    {
        $this->diagnosis = $diagnosis;

        return $this;
    }

    public function getAdmittingTeam(): ?string
    {
        return $this->admittingTeam;
    }

    public function setAdmittingTeam(?string $admittingTeam): self
    {
        $this->admittingTeam = $admittingTeam;

        return $this;
    }

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function setPriority(?Priority $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getEvaluatingResident(): ?string
    {
        return $this->evaluatingResident;
    }

    public function setEvaluatingResident(?string $evaluatingResident): self
    {
        $this->evaluatingResident = $evaluatingResident;

        return $this;
    }

    public function getSeniorSurgeon(): ?string
    {
        return $this->seniorSurgeon;
    }

    public function setSeniorSurgeon(?string $seniorSurgeon): self
    {
        $this->seniorSurgeon = $seniorSurgeon;

        return $this;
    }

    public function getApprovedBy(): ?User
    {
        return $this->approvedBy;
    }

    public function setApprovedBy(?User $approvedBy): self
    {
        $this->approvedBy = $approvedBy;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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
}
