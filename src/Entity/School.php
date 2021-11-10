<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SchoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=SchoolRepository::class)
 */
class School
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $subscriptionDate;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="school")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="school")
     */
    private $students;

    /**
     * @ORM\OneToMany(targetEntity=Vacations::class, mappedBy="school")
     */
    private $vacations;

    /**
     * @ORM\OneToMany(targetEntity=VacationLog::class, mappedBy="school")
     */
    private $vacationLogs;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->vacations = new ArrayCollection();
        $this->vacationLogs = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSubscriptionDate(): ?\DateTimeInterface
    {
        return $this->subscriptionDate;
    }

    public function setSubscriptionDate(?\DateTimeInterface $subscriptionDate): self
    {
        $this->subscriptionDate = $subscriptionDate;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setSchool($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSchool() === $this) {
                $user->setSchool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->setSchool($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getSchool() === $this) {
                $student->setSchool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Vacations[]
     */
    public function getVacations(): Collection
    {
        return $this->vacations;
    }

    public function addVacation(Vacations $vacation): self
    {
        if (!$this->vacations->contains($vacation)) {
            $this->vacations[] = $vacation;
            $vacation->setSchool($this);
        }

        return $this;
    }

    public function removeVacation(Vacations $vacation): self
    {
        if ($this->vacations->removeElement($vacation)) {
            // set the owning side to null (unless already changed)
            if ($vacation->getSchool() === $this) {
                $vacation->setSchool(null);
            }
        }

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
            $vacationLog->setSchool($this);
        }

        return $this;
    }

    public function removeVacationLog(VacationLog $vacationLog): self
    {
        if ($this->vacationLogs->removeElement($vacationLog)) {
            // set the owning side to null (unless already changed)
            if ($vacationLog->getSchool() === $this) {
                $vacationLog->setSchool(null);
            }
        }

        return $this;
    }
}
