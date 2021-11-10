<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Unique;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 * @UniqueEntity("no",
 *     message="Bu mail adresi daha önce kullanılmış."
 * )
 */
class Student
{
    use DateTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $no;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Vacations::class, mappedBy="student")
     */
    private $vacations;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=School::class, inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private $school;

    public function __construct()
    {
        $this->vacations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNo(): ?int
    {
        return $this->no;
    }

    public function setNo(int $no): self
    {
        $this->no = $no;

        return $this;
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

    public function setImage(string $image): self
    {
        $this->image = $image;

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
            $vacation->setStudent($this);
        }

        return $this;
    }

    public function removeVacation(Vacations $vacation): self
    {
        if ($this->vacations->removeElement($vacation)) {
            // set the owning side to null (unless already changed)
            if ($vacation->getStudent() === $this) {
                $vacation->setStudent(null);
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
