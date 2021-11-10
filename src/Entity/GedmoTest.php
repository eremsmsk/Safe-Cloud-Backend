<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Blameable\BlameableTrait;
use App\Entity\IpTraceable\IpTraceableTrait;
use App\Entity\Timestampable\TimestampableTrait;
use App\Entity\Translatable\langTrait;
use App\Repository\GedmoTestRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Loggable\LogGedmoTest;
use App\Entity\Translatable\LangGedmoTest;
use App\Resolver\GedmoTest\Mutation\CreateResolver;
use App\Resolver\GedmoTest\Mutation\UpdateResolver;
use App\Resolver\GedmoTest\Item\GetItemResolver;
use App\Resolver\GedmoTest\Collection\GetCollectionResolver;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"gedmo_test:output"}},
 *      denormalizationContext={"groups"={"gedmo_test:input"}},
 *      attributes={
 *          "pagination_type"="page"
 *      },
 *      graphql={
 *          "create"={
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "normalization_context"={"groups"={"gedmo_test:output"}},
 *              "denormalization_context"={"groups"={"gedmo_test:create"}},
 *              "mutation"=CreateResolver::class,
 *              "args"={
 *                  "name"={"type"="String!", "description"="ÖRN: 'Deneme'"},
 *                  "langs"={"type"="[InputGedmoTestLang!]", "description"=""}
 *              }
 *          },
 *          "update"={
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "normalization_context"={"groups"={"gedmo_test:output"}},
 *              "denormalization_context"={"groups"={"gedmo_test:update"}},
 *              "mutation"=UpdateResolver::class,
 *              "args"={
 *                  "id"={"type"="String!", "description"="Gedmo Test id 'si. ÖRN: '/tr/api/gedmo_tests/{id}'"},
 *                  "name"={"type"="String!", "description"="ÖRN: 'Deneme'"},
 *                  "langs"={"type"="[InputGedmoTestLang!]", "description"=""}
 *              }
 *          },
 *          "delete"={
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "normalization_context"={"groups"={"gedmo_test:output"}},
 *              "denormalization_context"={"groups"={"gedmo_test:delete"}}
 *          },
 *          "getGedmoTest"={
 *              "name"="getGedmoTest",
 *              "description"="Herkese Açık.",
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "normalization_context"={"groups"={"gedmo_test:output"}},
 *              "denormalization_context"={"groups"={"gedmo_test:item_query"}},
 *              "item_query"=GetItemResolver::class,
 *              "args"={
 *                  "slug"={"type"="String!", "description"="ÖRN: 'test'"}
 *              }
 *          },
 *          "getGedmoTests"={
 *              "name"="getGedmoTests",
 *              "description"="Herkese Açık.",
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "normalization_context"={"groups"={"gedmo_test:output", "timestampable:output"}},
 *              "denormalization_context"={"groups"={"gedmo_test:collection_query"}},
 *              "collection_query"=GetCollectionResolver::class,
 *              "args"={
 *                  "page"={"type"="Int!", "description"="1 veya 2"},
 *                  "itemsPerPage"={"type"="Int", "description"="10 veya 20 vb. Varsayılanı 100"},
 *                  "name"={"type"="String", "description"=""}
 *              }
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass=GedmoTestRepository::class)
 * @Gedmo\Loggable(logEntryClass=LogGedmoTest::class)
 * @Gedmo\TranslationEntity(class=LangGedmoTest::class)
 */
class GedmoTest implements Translatable
{
    use IpTraceableTrait;
    use BlameableTrait;
    use TimestampableTrait;

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"gedmo_test:input", "gedmo_test:output"})
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned()
     * @Gedmo\Translatable()
     * @Groups({"gedmo_test:input", "gedmo_test:output", "gedmo_test:create", "gedmo_test:update"})
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned()
     * @Gedmo\Translatable()
     * @Gedmo\Slug(fields={"name","id"})
     * @Groups({"gedmo_test:input", "gedmo_test:output"})
     */
    private $nameSlug;

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
     * @param string $nameSlug
     * @return $this
     */
    public function setNameSlug(string $nameSlug): self
    {
        $this->nameSlug = $nameSlug;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNameSlug(): ?string
    {
        return $this->nameSlug;
    }
}
