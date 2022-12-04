<?php
namespace App\Entity;
//@Assert\Range( min=1, max=100)
use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\TypeValidator;
/**
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 */
class Patient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Please provide card number")
     * @ORM\Column(type="string", length=20)
     */
    private $MRN;

    /**
     * @Assert\NotBlank(message="Please provide first Name")
     * @Assert\Regex(pattern="/^[a-z]+$/i",
     * htmlPattern="[a-zA-Z]+",
     * message="Only letters are allowed"
     * )
     * @ORM\Column(type="string", length=30)
     */
    private $fname;

    /**
     * @Assert\NotBlank(message="Please provide middle Name")
     * @Assert\Regex(pattern="/^[a-z]+$/i",
     * htmlPattern="[a-zA-Z]+",
     * message="Only letters are allowed"
     * )
     * @ORM\Column(type="string", length=30)
     */
    private $mname;

    /**
     * @Assert\Regex(pattern="/^[a-z]+$/i",
     * htmlPattern="[a-zA-Z]+",
     * message="Only letters are allowed")
     * @ORM\Column(type="string", length=30,nullable=true)
     */
    private $lname;
    /**
     * @Assert\NotBlank(message="Please select gender")
     * @ORM\Column(type="string", length=20)
     */
    private $gender;
    /**
     * @Assert\Length(
     * min=10,
     * max=10,
     * minMessage="The min length of the number must be 10 digit",
     * maxMessage="The max length of the number must be 10 digit"
     * )
     * @Assert\Regex(pattern="/^[0-9]+$/i",message="number only")
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=Appointment::class, mappedBy="patient", orphanRemoval=true)
     */
    private $appointments;


    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity=Admimssion::class, mappedBy="patient", orphanRemoval=true)
     */
    private $admimssions;




    /**
     * @ORM\Column(type="date")
     */
    private $dob;

  
    /**
     * @ORM\Column(type="date")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="patients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Slip::class, mappedBy="patient", orphanRemoval=true)
     */
    private $slips;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="patients")
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity=Zone::class, inversedBy="patients")
     */
    private $zone;

    /**
     * @ORM\ManyToOne(targetEntity=Woreda::class, inversedBy="patients")
     */
    private $woreda;

    /**
     * @ORM\OneToMany(targetEntity=Assessment::class, mappedBy="patient")
     */
    private $assessments;

    /**
     * @ORM\OneToMany(targetEntity=Encounter::class, mappedBy="patient")
     */
    private $encounters;



    public function __construct()
    {
      
        $this->appointments = new ArrayCollection();
     
        $this->admimssions = new ArrayCollection();
        $this->slips = new ArrayCollection();
        $this->assessments = new ArrayCollection();
        $this->encounters = new ArrayCollection();
    }


    

    public function __toString()
    {
        return $this->fname. " ".$this->mname." ".$this->lname; 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMRN(): ?string
    {
        return $this->MRN;
    }

    public function setMRN(string $MRN): self
    {
        $this->MRN = $MRN;

        return $this;
    }

    public function getFname(): ?string
    {
        return $this->fname;
    }

    public function setFname(string $fname): self
    {
        $this->fname = $fname;

        return $this;
    }

    public function getMname(): ?string
    {
        return $this->mname;
    }

    public function setMname(string $mname): self
    {
        $this->mname = $mname;

        return $this;
    }

    public function getLname(): ?string
    {
        return $this->lname;
    }

    public function setLname(string $lname): self
    {
        $this->lname = $lname;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

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
  



    public function getDob(): ?\DateTime
    {
        return $this->dob;
    }

    public function setDob(\DateTime $dob): self
    {
        $this->dob = $dob;

        return $this;
    }
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
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
            $appointment->setPatient($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getPatient() === $this) {
                $appointment->setPatient(null);
            }
        }

        return $this;
    }

 

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

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
            $admimssion->setPatient($this);
        }

        return $this;
    }

    public function removeAdmimssion(Admimssion $admimssion): self
    {
        if ($this->admimssions->removeElement($admimssion)) {
            // set the owning side to null (unless already changed)
            if ($admimssion->getPatient() === $this) {
                $admimssion->setPatient(null);
            }
        }

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
            $slip->setPatient($this);
        }

        return $this;
    }

    public function removeSlip(Slip $slip): self
    {
        if ($this->slips->removeElement($slip)) {
            // set the owning side to null (unless already changed)
            if ($slip->getPatient() === $this) {
                $slip->setPatient(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getWoreda(): ?Woreda
    {
        return $this->woreda;
    }

    public function setWoreda(?Woreda $woreda): self
    {
        $this->woreda = $woreda;

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
            $assessment->setPatient($this);
        }

        return $this;
    }

    public function removeAssessment(Assessment $assessment): self
    {
        if ($this->assessments->removeElement($assessment)) {
            // set the owning side to null (unless already changed)
            if ($assessment->getPatient() === $this) {
                $assessment->setPatient(null);
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
            $encounter->setPatient($this);
        }

        return $this;
    }

    public function removeEncounter(Encounter $encounter): self
    {
        if ($this->encounters->removeElement($encounter)) {
            // set the owning side to null (unless already changed)
            if ($encounter->getPatient() === $this) {
                $encounter->setPatient(null);
            }
        }

        return $this;
    }

}
