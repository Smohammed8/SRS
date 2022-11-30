<?php

namespace App\Entity;

use App\Repository\VitalSignRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VitalSignRepository::class)
 */
class VitalSign
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $temperature;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $heartRate;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $respiratoryRate;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $bloodPressure;

    /**
     * @ORM\ManyToOne(targetEntity=Encounter::class, inversedBy="vitalSigns")
     */
    private $encounter;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(?float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getHeartRate(): ?string
    {
        return $this->heartRate;
    }

    public function setHeartRate(?string $heartRate): self
    {
        $this->heartRate = $heartRate;

        return $this;
    }

    public function getRespiratoryRate(): ?string
    {
        return $this->respiratoryRate;
    }

    public function setRespiratoryRate(?string $respiratoryRate): self
    {
        $this->respiratoryRate = $respiratoryRate;

        return $this;
    }

    public function getBloodPressure(): ?string
    {
        return $this->bloodPressure;
    }

    public function setBloodPressure(?string $bloodPressure): self
    {
        $this->bloodPressure = $bloodPressure;

        return $this;
    }

    public function getEncounter(): ?Encounter
    {
        return $this->encounter;
    }

    public function setEncounter(?Encounter $encounter): self
    {
        $this->encounter = $encounter;

        return $this;
    }
}
