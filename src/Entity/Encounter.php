<?php

namespace App\Entity;

use App\Repository\EncounterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EncounterRepository::class)
 */
class Encounter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=EncounterType::class, inversedBy="encounters")
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="encounters")
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="encounters")
     */
    private $destination;

    /**
     * @ORM\ManyToOne(targetEntity=Referrals::class, inversedBy="encounters")
     */
    private $referout;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="encounters")
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="encounterUpdates")
     */
    private $updatedBy;

    /**
     * @ORM\ManyToOne(targetEntity=ReasonOfReferral::class, inversedBy="encounters")
     */
    private $referralReason;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $specifyReason;

 

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="refferedbyusers")
     */
    private $referredBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $referredAt;

    /**
     * @ORM\ManyToOne(targetEntity=DiseaseCategory::class, inversedBy="encounters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $diseaseCategory;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $encounterType;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="assignedEncounters")
     */
    private $assignedTo;

    /**
     * @ORM\OneToMany(targetEntity=VitalSign::class, mappedBy="encounter")
     */
    private $vitalSigns;

    public function __construct()
    {
        $this->vitalSigns = new ArrayCollection();
    }

    public function __toString()
    {
      return $this->patient;     
    
   
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?EncounterType
    {
        return $this->type;
    }

    public function setType(?EncounterType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
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

    public function getDestination(): ?Department
    {
        return $this->destination;
    }

    public function setDestination(?Department $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getReferout(): ?Referrals
    {
        return $this->referout;
    }

    public function setReferout(?Referrals $referout): self
    {
        $this->referout = $referout;

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

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getReferralReason(): ?ReasonOfReferral
    {
        return $this->referralReason;
    }

    public function setReferralReason(?ReasonOfReferral $referralReason): self
    {
        $this->referralReason = $referralReason;

        return $this;
    }

    public function getSpecifyReason(): ?string
    {
        return $this->specifyReason;
    }

    public function setSpecifyReason(?string $specifyReason): self
    {
        $this->specifyReason = $specifyReason;

        return $this;
    }

   

    public function getReferredBy(): ?User
    {
        return $this->referredBy;
    }

    public function setReferredBy(?User $referredBy): self
    {
        $this->referredBy = $referredBy;

        return $this;
    }

    public function getReferredAt(): ?\DateTimeInterface
    {
        return $this->referredAt;
    }

    public function setReferredAt(?\DateTimeInterface $referredAt): self
    {
        $this->referredAt = $referredAt;

        return $this;
    }

    public function getDiseaseCategory(): ?DiseaseCategory
    {
        return $this->diseaseCategory;
    }

    public function setDiseaseCategory(?DiseaseCategory $diseaseCategory): self
    {
        $this->diseaseCategory = $diseaseCategory;

        return $this;
    }

    public function getEncounterType(): ?string
    {
        return $this->encounterType;
    }

    public function setEncounterType(string $encounterType): self
    {
        $this->encounterType = $encounterType;

        return $this;
    }

    public function getAssignedTo(): ?User
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?User $assignedTo): self
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    /**
     * @return Collection<int, VitalSign>
     */
    public function getVitalSigns(): Collection
    {
        return $this->vitalSigns;
    }

    public function addVitalSign(VitalSign $vitalSign): self
    {
        if (!$this->vitalSigns->contains($vitalSign)) {
            $this->vitalSigns[] = $vitalSign;
            $vitalSign->setEncounter($this);
        }

        return $this;
    }

    public function removeVitalSign(VitalSign $vitalSign): self
    {
        if ($this->vitalSigns->removeElement($vitalSign)) {
            // set the owning side to null (unless already changed)
            if ($vitalSign->getEncounter() === $this) {
                $vitalSign->setEncounter(null);
            }
        }

        return $this;
    }
}
