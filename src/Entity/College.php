<?php

namespace App\Entity;

use App\Repository\CollegeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CollegeRepository::class)
 */
class College
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
     * @ORM\OneToMany(targetEntity=Campus::class, mappedBy="college")
     */
    private $campuses;

    public function __construct()
    {
        $this->campuses = new ArrayCollection();
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

    /**
     * @return Collection<int, Campus>
     */
    public function getCampuses(): Collection
    {
        return $this->campuses;
    }

    public function addCampus(Campus $campus): self
    {
        if (!$this->campuses->contains($campus)) {
            $this->campuses[] = $campus;
            $campus->setCollege($this);
        }

        return $this;
    }

    public function removeCampus(Campus $campus): self
    {
        if ($this->campuses->removeElement($campus)) {
            // set the owning side to null (unless already changed)
            if ($campus->getCollege() === $this) {
                $campus->setCollege(null);
            }
        }

        return $this;
    }
}
