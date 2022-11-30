<?php

namespace App\Entity;

use App\Repository\BedTransferHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BedTransferHistoryRepository::class)
 */
class BedTransferHistory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Admimssion::class, inversedBy="bedTransferHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $admission;

    /**
     * @ORM\ManyToOne(targetEntity=Bed::class, inversedBy="bedTransferHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $oldBed;

    /**
     * @ORM\ManyToOne(targetEntity=Bed::class, inversedBy="bedTransferHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $newBed;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $transferredAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOldBed(): ?Bed
    {
        return $this->oldBed;
    }

    public function setOldBed(?Bed $oldBed): self
    {
        $this->oldBed = $oldBed;

        return $this;
    }

    public function getNewBed(): ?Bed
    {
        return $this->newBed;
    }

    public function setNewBed(?Bed $newBed): self
    {
        $this->newBed = $newBed;

        return $this;
    }

    public function getTransferredAt(): ?\DateTimeImmutable
    {
        return $this->transferredAt;
    }

    public function setTransferredAt(\DateTimeImmutable $transferredAt): self
    {
        $this->transferredAt = $transferredAt;

        return $this;
    }
}
