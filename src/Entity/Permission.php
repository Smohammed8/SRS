<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PermissionRepository::class)
 */
class Permission
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
     * @ORM\Column(type="string", length=20)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=UserGroup::class, inversedBy="permissions")
     */
    private $userGroup;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->userGroup = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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
     * @return Collection|UserGroup[]
     */
    public function getUserGroup(): Collection
    {
        return $this->userGroup;
    }

    public function addUserGroup(UserGroup $userGroup): self
    {
       
         if (!$this->userGroup->contains($userGroup)) {
            $this->userGroup[] = $userGroup;
            $userGroup->addPermission($this);
        }


        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup): self
    {
        $this->userGroup->removeElement($userGroup);

        if ($this->userGroup->contains($userGroup)) {
            $this->userGroup->removeElement($userGroup);
            $userGroup->removePermission($this);
        }

        return $this;
    }

}
