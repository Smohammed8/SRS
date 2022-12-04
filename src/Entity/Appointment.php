<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppointmentRepository::class)
 */
class Appointment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="appointments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $reason;

    /**
     * @ORM\Column(type="datetime")
     */
    private $appointAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $appointedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="appointments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalDays;

 

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $shift;



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

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getAppointAt(): ?\DateTimeInterface
    {
        return $this->appointAt;
    }

    public function setAppointAt(\DateTimeInterface $appointAt): self
    {
        $this->appointAt = $appointAt;

        return $this;
    }

    public function getAppointedAt(): ?\DateTimeInterface
    {
        return $this->appointedAt;
    }

    public function setAppointedAt(\DateTimeInterface $appointedAt): self
    {
        $this->appointedAt = $appointedAt;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }



  
    public function getTotalDays(): ?int
    {
        return $this->totalDays;
    }

    public function setTotalDays(?int $totalDays): self
    {
        $this->totalDays = $totalDays;

        return $this;
    }

    public function getShift(): ?string
    {
        return $this->shift;
    }

    public function setShift(string $shift): self
    {
        $this->shift = $shift;

        return $this;
    }


}
