<?php

namespace App\Resolver\GedmoTest\Mutation;

use ApiPlatform\Core\GraphQl\Resolver\MutationResolverInterface;
use App\Entity\GedmoTest;
use App\Entity\Sluggable\CreateSlug;
use App\Entity\Translatable\LangGedmoTest;
use App\Repository\GedmoTestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;

final class UpdateResolver implements MutationResolverInterface
{
    /** @var GedmoTestRepository */
    private $gedmoTestRepository;
    /** @var TranslationRepository */
    private $langGedmoRepository;
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(GedmoTestRepository $gedmoTestRepository, EntityManagerInterface $em)
    {
        $this->gedmoTestRepository = $gedmoTestRepository;
        /** @var TranslationRepository $repo */
        $repo = $em->getRepository(LangGedmoTest::class);
        $this->langGedmoRepository = $repo;
        $this->em = $em;
    }

    /**
     * @param GedmoTest|null $item
     * @param array<string, mixed> $context
     * @return GedmoTest|null
     */
    public function __invoke($item, array $context): ?GedmoTest
    {
        $postData = $context['args']['input'];

        if (!is_null($item)){
            if (array_key_exists("langs", $postData) && is_array($postData["langs"])){
                foreach ($postData["langs"] as $lang){
                    $this->langGedmoRepository
                        ->translate($item,"name",$lang["lang"],$lang["name"])
                        ->translate($item,"nameSlug",$lang["lang"],(new CreateSlug($lang["name"],$item))->getSlug());
                }
            }
        }

        return $item;
    }
}