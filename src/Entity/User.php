<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Resolver\User\Mutation\CreateResolver;
use App\Resolver\User\Item\GetItemResolver;
use App\Resolver\User\Collection\GetCollectionResolver;


/**
 * @ApiResource(
 *      normalizationContext={"groups"={"user:output"}},
 *      denormalizationContext={"groups"={"user:input"}},
 *      attributes={
 *          "pagination_type"="page"
 *      },
 *      graphql={
 *          "create"={
 *              "description"="Herkeze Açık.",
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "normalization_context"={"groups"={"user:output"}},
 *              "denormalization_context"={"groups"={"user:create"}},
 *              "mutation"=CreateResolver::class,
 *              "args"={
 *                  "email"={"type"="String!", "description"="ÖRN: 'test@test.com'"},
 *                  "password"={"type"="String!", "description"="ÖRN: '0123456'"},
 *                  "groups"={"type"="[String!]", "description"="ÖRN: ['/tr/api/groups/{id}', '/tr/api/groups/{id}']"}
 *              }
 *          },
 *          "getUser"={
 *              "name"="getUser",
 *              "description"="Herkese Açık.",
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "item_query"=GetItemResolver::class,
 *              "normalization_context"={"groups"={"user:output"}},
 *              "args"={
 *                  "id"={"type"="String!", "description"="Kullanıcı id 'si. ÖRN: '/tr/api/users/{id}'"}
 *              }
 *          },
 *          "getUsers"={
 *              "name"="getUsers",
 *              "description"="Herkese Açık.",
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "collection_query"=GetCollectionResolver::class,
 *              "normalization_context"={"groups"={"user:output"}},
 *              "args"={
 *                  "page"={"type"="Int!", "description"="1 veya 2"},
 *                  "itemsPerPage"={"type"="Int", "description"="10 veya 20 vb. Varsayılanı 100"}
 *              }
 *          }
 *      }
 * )
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 *
 * @UniqueEntity(
 *     fields={"username", "email"},
 *     message="Bu mail adresi daha önce kullanılmış."
 * )
 */
class User extends BaseUser
{
    // TODO: USER MODEL ALANLARI BAŞLANGIÇ -----------------------------------------------

    use DateTrait;

    /**
     * @var string
     * @Groups({"user:input", "user:output", "user:create"})
     */
    protected $email;

    /**
     * @var string
     * @Groups({"user:input", "user:create"})
     */
    protected $password;

    // TODO: USER MODEL ALANLARI BİTİŞ ----------------------------------------------------

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int $id
     * @Groups({"user:input", "user:output"})
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Group")
     * @ORM\JoinTable(name="user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     * @var ArrayCollection<int, Group>
     * @Groups({"user:input", "user:output", "user:create"})
     */
    protected $groups;

    /**
     * @ORM\OneToMany(targetEntity=Vacations::class, mappedBy="user")
     */
    private $vacations;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="user")
     */
    private $students;

    /**
     * @ORM\ManyToOne(targetEntity=School::class, inversedBy="users")
     */
    private $school;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->vacations = new ArrayCollection();
        $this->students = new ArrayCollection();
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
            $vacation->setUser($this);
        }

        return $this;
    }

    public function removeVacation(Vacations $vacation): self
    {
        if ($this->vacations->removeElement($vacation)) {
            // set the owning side to null (unless already changed)
            if ($vacation->getUser() === $this) {
                $vacation->setUser(null);
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
            $student->setUser($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getUser() === $this) {
                $student->setUser(null);
            }
        }

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