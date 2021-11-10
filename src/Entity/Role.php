<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Resolver\Role\Mutation\CreateResolver;
use App\Resolver\Role\Mutation\UpdateResolver;
use App\Resolver\Role\Collection\GetCollectionResolver;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"role:output"}},
 *      denormalizationContext={"groups"={"role:input"}},
 *      attributes={
 *          "pagination_type"="page"
 *      },
 *      graphql={
 *          "create"={
 *              "description"="Sadece MAXITHINGS erişebilir.",
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "security_message"="Erişim yetkiniz bulunmamaktadır.",
 *              "normalization_context"={"groups"={"role:output"}},
 *              "denormalization_context"={"groups"={"role:create"}},
 *              "mutation"=CreateResolver::class,
 *              "args"={
 *                  "role"={"type"="String!", "description"="ÖRN: 'ROLE_ADMIN'"},
 *                  "name"={"type"="String!", "description"="ÖRN: 'Yönetici'"}
 *              }
 *          },
 *          "update"={
 *              "description"="Sadece MAXITHINGS erişebilir.",
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "security_message"="Erişim yetkiniz bulunmamaktadır.",
 *              "normalization_context"={"groups"={"role:output"}},
 *              "denormalization_context"={"groups"={"role:update"}},
 *              "mutation"=UpdateResolver::class,
 *              "args"={
 *                  "id"={"type"="String!", "description"="ÖRN: '/tr/api/roles/{role}'"},
 *                  "name"={"type"="String!", "description"="ÖRN: 'Yönetici'"}
 *              }
 *          },
 *          "getRoles"={
 *              "name"="getRoles",
 *              "description"="Sadece ADMIN, SUPER_ADMIN, MAXITHINGS erişebilir.",
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY') or is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_MAXITHINGS')",
 *              "security_message"="Erişim yetkiniz bulunmamaktadır.",
 *              "collection_query"=GetCollectionResolver::class,
 *              "normalization_context"={"groups"={"role:output"}},
 *              "args"={
 *                  "page"={"type"="Int!", "description"="1 veya 2"},
 *                  "itemsPerPage"={"type"="Int", "description"="10 veya 20 vb. Varsayılanı 100"},
 *              }
 *          }
 *      }
 * )
 *
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 */
class Role
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @var string|null $id
     * @Groups({"role:input", "role:output"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string|null $name
     * @Groups({"role:input", "role:output", "role:create", "role:update"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, mappedBy="subRole")
     * @var ArrayCollection<int, Group>
     * @Groups({"role:input"})
     */
    private $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName() ?? "";
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

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

    /**
     * @return ArrayCollection<int, Group>
     */
    public function getGroups(): ArrayCollection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addRole($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeRole($this);
        }

        return $this;
    }

}
