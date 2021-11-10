<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\VacationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=VacationsRepository::class)
 */
class Vacations
{
    use DateTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="vacations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity=VacationType::class, inversedBy="vacations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vacationType;

    /**
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="date")
     */
    private $endDate;

    /**
     * @ORM\Column(type="time")
     */
    private $startTime;

    /**
     * @ORM\Column(type="time")
     */
    private $endTime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $barcode;

    /**
     * @ORM\OneToMany(targetEntity=VacationLog::class, mappedBy="vacation")
     */
    private $vacationLogs;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="vacations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled=1;

    /**
     * @ORM\ManyToOne(targetEntity=School::class, inversedBy="vacations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $school;

    public function __construct()
    {
        $this->vacationLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getVacationType(): ?VacationType
    {
        return $this->vacationType;
    }

    public function setVacationType(?VacationType $vacationType): self
    {
        $this->vacationType = $vacationType;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    /**
     * @return Collection|VacationLog[]
     */
    public function getVacationLogs(): Collection
    {
        return $this->vacationLogs;
    }

    public function addVacationLog(VacationLog $vacationLog): self
    {
        if (!$this->vacationLogs->contains($vacationLog)) {
            $this->vacationLogs[] = $vacationLog;
            $vacationLog->setVacation($this);
        }

        return $this;
    }

    public function removeVacationLog(VacationLog $vacationLog): self
    {
        if ($this->vacationLogs->removeElement($vacationLog)) {
            // set the owning side to null (unless already changed)
            if ($vacationLog->getVacation() === $this) {
                $vacationLog->setVacation(null);
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

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): self
    {
        $this->school = $school;

        return $this;
    }
}
