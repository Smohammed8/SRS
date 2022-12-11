<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    /**
     * @ORM\OneToMany(targetEntity=ProgramLevel::class, mappedBy="department")
     */
    private $programLevels;



    public function __construct()
    {
    
        $this->slips = new ArrayCollection();
    
        $this->programLevels = new ArrayCollection();
      
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }


    /**
     * @return Collection<int, ProgramLevel>
     */
    public function getProgramLevels(): Collection
    {
        return $this->programLevels;
    }

    public function addProgramLevel(ProgramLevel $programLevel): self
    {
        if (!$this->programLevels->contains($programLevel)) {
            $this->programLevels[] = $programLevel;
            $programLevel->setDepartment($this);
        }

        return $this;
    }

    public function removeProgramLevel(ProgramLevel $programLevel): self
    {
        if ($this->programLevels->removeElement($programLevel)) {
            // set the owning side to null (unless already changed)
            if ($programLevel->getDepartment() === $this) {
                $programLevel->setDepartment(null);
            }
        }

        return $this;
    }


}
