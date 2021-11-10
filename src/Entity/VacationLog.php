<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\VacationLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=VacationLogRepository::class)
 * @ORM\Table(name="vacation_log",options={"collate"="utf8_general_ci"}, uniqueConstraints={
 *      @ORM\UniqueConstraint(name="unique_vacation_log", columns={"vacation_id", "date"})
 * })
 */
class VacationLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity=Vacations::class, inversedBy="vacationLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vacation;

    /**
     * @ORM\ManyToOne(targetEntity=School::class, inversedBy="vacationLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $school;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getVacation(): ?Vacations
    {
        return $this->vacation;
    }

    public function setVacation(?Vacations $vacation): self
    {
        $this->vacation = $vacation;

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
