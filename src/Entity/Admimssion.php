<?php

namespace App\Entity;

use App\Repository\AdmimssionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdmimssionRepository::class)
 */
class Admimssion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="admimssions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dischargedAt;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $duration;



    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="admimssions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;



    /**
     * @ORM\ManyToOne(targetEntity=Admimssion::class, inversedBy="admimssions")
     */
    private $admission;

    /**
     * @ORM\OneToMany(targetEntity=Admimssion::class, mappedBy="admission")
     */
    private $admimssions;

    /**
     * @ORM\ManyToOne(targetEntity=AdmimssionType::class, inversedBy="admimssions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $remark;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCheckedIn;

    /**
     * @ORM\Column(type="boolean")
     */
    private $needOxgen;



    public function __construct()
    {
      
        $this->admimssions = new ArrayCollection();
     
    }

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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDischargedAt(): ?\DateTime
    {
        return $this->dischargedAt;
    }

    public function setDischargedAt(?\DateTime $dischargedAt): self
    {
        $this->dischargedAt = $dischargedAt;

        return $this;
    }

 

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }


 
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
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



    public function getAdmission(): ?self
    {
        return $this->admission;
    }

    public function setAdmission(?self $admission): self
    {
        $this->admission = $admission;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getAdmimssions(): Collection
    {
        return $this->admimssions;
    }

    public function addAdmimssion(self $admimssion): self
    {
        if (!$this->admimssions->contains($admimssion)) {
            $this->admimssions[] = $admimssion;
            $admimssion->setAdmission($this);
        }

        return $this;
    }

    public function removeAdmimssion(self $admimssion): self
    {
        if ($this->admimssions->removeElement($admimssion)) {
            // set the owning side to null (unless already changed)
            if ($admimssion->getAdmission() === $this) {
                $admimssion->setAdmission(null);
            }
        }

        return $this;
    }

    public function getType(): ?AdmimssionType
    {
        return $this->type;
    }

    public function setType(?AdmimssionType $type): self
    {
        $this->type = $type;

        return $this;
    }

  

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }

    public function getIsCheckedIn(): ?bool
    {
        return $this->isCheckedIn;
    }

    public function setIsCheckedIn(bool $isCheckedIn): self
    {
        $this->isCheckedIn = $isCheckedIn;

        return $this;
    }

    public function getNeedOxgen(): ?bool
    {
        return $this->needOxgen;
    }

    public function setNeedOxgen(bool $needOxgen): self
    {
        $this->needOxgen = $needOxgen;

        return $this;
    }


}
