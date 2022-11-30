<?php

namespace App\Entity;

use App\Repository\BedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BedRepository::class)
 */
class Bed
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="beds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $room;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFunctional;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $accessibility;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $currentStatus;

    /**
     * @ORM\OneToMany(targetEntity=Admimssion::class, mappedBy="bed", orphanRemoval=true)
     */
    private $admimssions;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="beds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $registeredBy;

    /**
     * @ORM\OneToMany(targetEntity=BedTransferHistory::class, mappedBy="oldBed", orphanRemoval=true)
     */
    private $bedTransferHistories;

  

    public function __construct()
    {
        $this->admimssions = new ArrayCollection();
        $this->bedTransferHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString()
    {
   return $this->name;     
    
   
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

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getIsFunctional(): ?bool
    {
        return $this->isFunctional;
    }

    public function setIsFunctional(bool $isFunctional): self
    {
        $this->isFunctional = $isFunctional;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAccessibility(): ?string
    {
        return $this->accessibility;
    }

    public function setAccessibility(string $accessibility): self
    {
        $this->accessibility = $accessibility;

        return $this;
    }

    public function getCurrentStatus(): ?string
    {
        return $this->currentStatus;
    }

    public function setCurrentStatus(?string $currentStatus): self
    {
        $this->currentStatus = $currentStatus;

        return $this;
    }

    /**
     * @return Collection|Admimssion[]
     */
    public function getAdmimssions(): Collection
    {
        return $this->admimssions;
    }

    public function addAdmimssion(Admimssion $admimssion): self
    {
        if (!$this->admimssions->contains($admimssion)) {
            $this->admimssions[] = $admimssion;
            $admimssion->setBed($this);
        }

        return $this;
    }

    public function removeAdmimssion(Admimssion $admimssion): self
    {
        if ($this->admimssions->removeElement($admimssion)) {
            // set the owning side to null (unless already changed)
            if ($admimssion->getBed() === $this) {
                $admimssion->setBed(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getRegisteredBy(): ?User
    {
        return $this->registeredBy;
    }

    public function setRegisteredBy(?User $registeredBy): self
    {
        $this->registeredBy = $registeredBy;

        return $this;
    }

    /**
     * @return Collection<int, BedTransferHistory>
     */
    public function getBedTransferHistories(): Collection
    {
        return $this->bedTransferHistories;
    }

    public function addBedTransferHistory(BedTransferHistory $bedTransferHistory): self
    {
        if (!$this->bedTransferHistories->contains($bedTransferHistory)) {
            $this->bedTransferHistories[] = $bedTransferHistory;
            $bedTransferHistory->setOldBed($this);
        }

        return $this;
    }

    public function removeBedTransferHistory(BedTransferHistory $bedTransferHistory): self
    {
        if ($this->bedTransferHistories->removeElement($bedTransferHistory)) {
            // set the owning side to null (unless already changed)
            if ($bedTransferHistory->getOldBed() === $this) {
                $bedTransferHistory->setOldBed(null);
            }
        }

        return $this;
    }

 


}
