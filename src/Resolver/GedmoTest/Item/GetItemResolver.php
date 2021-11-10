<?php

namespace App\Resolver\GedmoTest\Item;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use App\Entity\GedmoTest;
use App\Repository\GedmoTestRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

final class GetItemResolver implements QueryItemResolverInterface
{
    /** @var GedmoTestRepository */
    private $gedmoTestRepository;
    /** @var TranslatorInterface */
    private $translator;

    public function __construct(TranslatorInterface $translator, GedmoTestRepository $gedmoTestRepository)
    {
        $this->gedmoTestRepository = $gedmoTestRepository;
        $this->translator = $translator;
    }

    /**
     * @param GedmoTest|null $item
     * @param array<string, mixed> $context
     * @return GedmoTest|null
     */
    public function __invoke($item, array $context): ?GedmoTest
    {
        $postData = $context["args"];

        try {
            $item = $this->gedmoTestRepository->createQueryBuilder("gt")
                ->where("gt.nameSlug=:nameSlug")
                ->setParameter("nameSlug",$postData["slug"])
                ->getQuery()
                ->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
                ->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE,$this->translator->getLocale())
                ->setHint(\Gedmo\Translatable\TranslatableListener::HINT_FALLBACK,1)
                ->getOneOrNullResult();
        }catch (\Exception $exception){
            throw new BadRequestHttpException($this->translator->trans("recordNotFound"));
        }

        return $item;
    }
}