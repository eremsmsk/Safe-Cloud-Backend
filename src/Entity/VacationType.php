<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\VacationTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=VacationTypeRepository::class)
 */
class VacationType
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
     * @ORM\OneToMany(targetEntity=Vacations::class, mappedBy="vacationType")
     */
    private $vacations;

    public function __construct()
    {
        $this->vacations = new ArrayCollection();
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
            $vacation->setVacationType($this);
        }

        return $this;
    }

    public function removeVacation(Vacations $vacation): self
    {
        if ($this->vacations->removeElement($vacation)) {
            // set the owning side to null (unless already changed)
            if ($vacation->getVacationType() === $this) {
                $vacation->setVacationType(null);
            }
        }

        return $this;
    }
}
