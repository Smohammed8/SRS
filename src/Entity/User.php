<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $fatherName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $sex;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $registeredBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\ManyToMany(targetEntity=UserGroup::class, inversedBy="users")
     */
    private $userGroup;

    /**
     * @ORM\OneToMany(targetEntity=Patient::class, mappedBy="user")
     */
    private $patients;

    /**
     * @ORM\OneToMany(targetEntity=Admimssion::class, mappedBy="user")
     */
    private $admimssions;



    /**
     * @ORM\OneToMany(targetEntity=Appointment::class, mappedBy="user", orphanRemoval=true)
     */
    private $appointments;


    /**
     * @ORM\OneToMany(targetEntity=Slip::class, mappedBy="approvedBy")
     */
    private $slips;

    /**
     * @ORM\OneToMany(targetEntity=Slip::class, mappedBy="user", orphanRemoval=true)
     */
    private $createdBy;

    /**
     * @ORM\OneToMany(targetEntity=UserGroup::class, mappedBy="updatedBy", orphanRemoval=true)
     */
    private $userGroups;

  

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOnline;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity=Assessment::class, mappedBy="physcian")
     */
    private $assessments;

    /**
     * @ORM\OneToMany(targetEntity=Encounter::class, mappedBy="createdBy")
     */
    private $encounters;

    /**
     * @ORM\OneToMany(targetEntity=Encounter::class, mappedBy="updatedBy")
     */
    private $encounterUpdates;

    /**
     * @ORM\OneToMany(targetEntity=Encounter::class, mappedBy="referredBy")
     */
    private $refferedbyusers;

    /**
     * @ORM\OneToMany(targetEntity=Encounter::class, mappedBy="assignedTo")
     */
    private $assignedEncounters;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="createdBy")
     */
    private $students;

    public function __construct()
    {
        $this->userGroup = new ArrayCollection();
        $this->patients = new ArrayCollection();
        $this->admimssions = new ArrayCollection();
     
        $this->appointments = new ArrayCollection();
        $this->slips = new ArrayCollection();
        $this->createdBy = new ArrayCollection();
        $this->userGroups = new ArrayCollection();
        $this->assessments = new ArrayCollection();
        $this->encounters = new ArrayCollection();
        $this->encounterUpdates = new ArrayCollection();
        $this->refferedbyusers = new ArrayCollection();
        $this->assignedEncounters = new ArrayCollection();
        $this->students = new ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->getName();
    }
 

    public function getName()
    {
     return $this->firstName." ".$this->fatherName;
      
    }
    public function getFullName()
    {
       return $this->getName()." ".$this->lastName;
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFatherName(): ?string
    {
        return $this->fatherName;
    }

    public function setFatherName(string $fatherName): self
    {
        $this->fatherName = $fatherName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getRegisteredBy(): ?self
    {
        return $this->registeredBy;
    }

    public function setRegisteredBy(?self $registeredBy): self
    {
        $this->registeredBy = $registeredBy;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

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
        }

        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup): self
    {
        $this->userGroup->removeElement($userGroup);

        return $this;
    }

    /**
     * @return Collection|Patient[]
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients[] = $patient;
            $patient->setUser($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getUser() === $this) {
                $patient->setUser(null);
            }
        }

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
            $admimssion->setUser($this);
        }

        return $this;
    }

    public function removeAdmimssion(Admimssion $admimssion): self
    {
        if ($this->admimssions->removeElement($admimssion)) {
            // set the owning side to null (unless already changed)
            if ($admimssion->getUser() === $this) {
                $admimssion->setUser(null);
            }
        }

        return $this;
    }

   
    /**
     * @return Collection|Appointment[]
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): self
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments[] = $appointment;
            $appointment->setUser($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getUser() === $this) {
                $appointment->setUser(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, Slip>
     */
    public function getSlips(): Collection
    {
        return $this->slips;
    }

    public function addSlip(Slip $slip): self
    {
        if (!$this->slips->contains($slip)) {
            $this->slips[] = $slip;
            $slip->setApprovedBy($this);
        }

        return $this;
    }

    public function removeSlip(Slip $slip): self
    {
        if ($this->slips->removeElement($slip)) {
            // set the owning side to null (unless already changed)
            if ($slip->getApprovedBy() === $this) {
                $slip->setApprovedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Slip>
     */
    public function getCreatedBy(): Collection
    {
        return $this->createdBy;
    }

    public function addCreatedBy(Slip $createdBy): self
    {
        if (!$this->createdBy->contains($createdBy)) {
            $this->createdBy[] = $createdBy;
            $createdBy->setUser($this);
        }

        return $this;
    }

    public function removeCreatedBy(Slip $createdBy): self
    {
        if ($this->createdBy->removeElement($createdBy)) {
            // set the owning side to null (unless already changed)
            if ($createdBy->getUser() === $this) {
                $createdBy->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserGroup>
     */
    public function getUserGroups(): Collection
    {
        return $this->userGroups;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsOnline(): ?bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(bool $isOnline): self
    {
        $this->isOnline = $isOnline;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Assessment>
     */
    public function getAssessments(): Collection
    {
        return $this->assessments;
    }

    public function addAssessment(Assessment $assessment): self
    {
        if (!$this->assessments->contains($assessment)) {
            $this->assessments[] = $assessment;
            $assessment->setPhyscian($this);
        }

        return $this;
    }

    public function removeAssessment(Assessment $assessment): self
    {
        if ($this->assessments->removeElement($assessment)) {
            // set the owning side to null (unless already changed)
            if ($assessment->getPhyscian() === $this) {
                $assessment->setPhyscian(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Encounter>
     */
    public function getEncounters(): Collection
    {
        return $this->encounters;
    }

    public function addEncounter(Encounter $encounter): self
    {
        if (!$this->encounters->contains($encounter)) {
            $this->encounters[] = $encounter;
            $encounter->setCreatedBy($this);
        }

        return $this;
    }

    public function removeEncounter(Encounter $encounter): self
    {
        if ($this->encounters->removeElement($encounter)) {
            // set the owning side to null (unless already changed)
            if ($encounter->getCreatedBy() === $this) {
                $encounter->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Encounter>
     */
    public function getEncounterUpdates(): Collection
    {
        return $this->encounterUpdates;
    }

    public function addEncounterUpdate(Encounter $encounterUpdate): self
    {
        if (!$this->encounterUpdates->contains($encounterUpdate)) {
            $this->encounterUpdates[] = $encounterUpdate;
            $encounterUpdate->setUpdatedBy($this);
        }

        return $this;
    }

    public function removeEncounterUpdate(Encounter $encounterUpdate): self
    {
        if ($this->encounterUpdates->removeElement($encounterUpdate)) {
            // set the owning side to null (unless already changed)
            if ($encounterUpdate->getUpdatedBy() === $this) {
                $encounterUpdate->setUpdatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Encounter>
     */
    public function getRefferedbyusers(): Collection
    {
        return $this->refferedbyusers;
    }

    public function addRefferedbyuser(Encounter $refferedbyuser): self
    {
        if (!$this->refferedbyusers->contains($refferedbyuser)) {
            $this->refferedbyusers[] = $refferedbyuser;
            $refferedbyuser->setReferredBy($this);
        }

        return $this;
    }

    public function removeRefferedbyuser(Encounter $refferedbyuser): self
    {
        if ($this->refferedbyusers->removeElement($refferedbyuser)) {
            // set the owning side to null (unless already changed)
            if ($refferedbyuser->getReferredBy() === $this) {
                $refferedbyuser->setReferredBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Encounter>
     */
    public function getAssignedEncounters(): Collection
    {
        return $this->assignedEncounters;
    }

    public function addAssignedEncounter(Encounter $assignedEncounter): self
    {
        if (!$this->assignedEncounters->contains($assignedEncounter)) {
            $this->assignedEncounters[] = $assignedEncounter;
            $assignedEncounter->setAssignedTo($this);
        }

        return $this;
    }

    public function removeAssignedEncounter(Encounter $assignedEncounter): self
    {
        if ($this->assignedEncounters->removeElement($assignedEncounter)) {
            // set the owning side to null (unless already changed)
            if ($assignedEncounter->getAssignedTo() === $this) {
                $assignedEncounter->setAssignedTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->setCreatedBy($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getCreatedBy() === $this) {
                $student->setCreatedBy(null);
            }
        }

        return $this;
    }


    

}
