<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\GroupRepository;
use App\Resolver\Group\Mutation\CreateResolver;
use App\Resolver\Group\Mutation\UpdateResolver;
use App\Resolver\Group\Collection\GetCollectionResolver;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"group:output"}},
 *      denormalizationContext={"groups"={"group:input"}},
 *      attributes={
 *          "pagination_type"="page"
 *      },
 *      graphql={
 *          "create"={
 *              "description"="Sadece SUPER_ADMIN erişebilir.",
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "security_message"="Erişim yetkiniz bulunmamaktadır.",
 *              "normalization_context"={"groups"={"group:output"}},
 *              "denormalization_context"={"groups"={"group:create"}},
 *              "mutation"=CreateResolver::class,
 *              "args"={
 *                  "name"={"type"="String!", "description"="ÖRN: Yönetici"},
 *                  "subRole"={"type"="[String!]!", "description"="ÖRN: ['/tr/api/roles/{role}','/tr/api/roles/{role}']"}
 *              }
 *          },
 *          "update"={
 *              "description"="Sadece SUPER_ADMIN erişebilir.",
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "security_message"="Erişim yetkiniz bulunmamaktadır.",
 *              "normalization_context"={"groups"={"group:output"}},
 *              "denormalization_context"={"groups"={"group:update"}},
 *              "mutation"=UpdateResolver::class,
 *              "args"={
 *                  "id"={"type"="String!", "description"="ÖRN: /tr/api/roles/{role}"},
 *                  "name"={"type"="String!", "description"="ÖRN: Yönetici"},
 *                  "subRole"={"type"="[String!]!", "description"="ÖRN: ['/tr/api/roles/{role}','/tr/api/roles/{role}']"}
 *              }
 *          },
 *          "getGroups"={
 *              "name"="getGroups",
 *              "description"="Sadece SUPER_ADMIN erişebilir.",
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "security_message"="Erişim yetkiniz bulunmamaktadır.",
 *              "collection_query"=GetCollectionResolver::class,
 *              "normalization_context"={"groups"={"group:output"}},
 *              "args"={
 *                  "page"={"type"="Int!", "description"="1 veya 2"},
 *                  "itemsPerPage"={"type"="Int", "description"="10 veya 20 vb. Varsayılanı 100"},
 *              }
 *          }
 *      }
 * )
 *
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="`group`")
 */
class Group extends BaseGroup
{
    // TODO: GROUP MODEL ALANLARI BİTİŞ ----------------------------------------------------

    /**
     * @var string|null
     * @Groups({"group:input", "group:output", "group:create", "group:update"})
     */
    protected $name;

    /**
     * @var array<string, mixed>|null
     * @Groups({"group:input", "group:output"})
     */
    protected $roles;

    // TODO: GROUP MODEL ALANLARI BİTİŞ ----------------------------------------------------

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     * @var int $id
     * @Groups({"group:input", "group:output"})
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="groups")
     * @var ArrayCollection<int, Role>
     * @Groups({"group:input", "group:output", "group:create", "group:update"})
     */
    private $subRole;

    public function __construct()
    {
        parent::__construct("");
        $this->subRole = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName() ?? "";
    }

    /**
     * @return ArrayCollection<int, Role>
     */
    public function getSubRole(): ArrayCollection
    {
        return $this->subRole;
    }

    public function addSubRole(Role $role): self
    {
        if (!$this->subRole->contains($role)) {
            $this->subRole[] = $role;
        }

        return $this;
    }

    public function removeSubRole(Role $role): self
    {
        $this->subRole->removeElement($role);

        return $this;
    }
}