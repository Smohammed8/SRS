<?php

namespace App\Entity;

use App\Repository\GradingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GradingRepository::class)
 */
class Grading
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $letter;

    /**
     * @ORM\Column(type="integer")
     */
    private $startMark;

    /**
     * @ORM\Column(type="integer")
     */
    private $endMark;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $fixedMark;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLetter(): ?string
    {
        return $this->letter;
    }

    public function setLetter(string $letter): self
    {
        $this->letter = $letter;

        return $this;
    }

    public function getStartMark(): ?int
    {
        return $this->startMark;
    }

    public function setStartMark(int $startMark): self
    {
        $this->startMark = $startMark;

        return $this;
    }

    public function getEndMark(): ?int
    {
        return $this->endMark;
    }

    public function setEndMark(int $endMark): self
    {
        $this->endMark = $endMark;

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

    public function getFixedMark(): ?string
    {
        return $this->fixedMark;
    }

    public function setFixedMark(?string $fixedMark): self
    {
        $this->fixedMark = $fixedMark;

        return $this;
    }
}
