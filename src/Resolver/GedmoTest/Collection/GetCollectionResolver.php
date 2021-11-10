<?php


namespace App\Resolver\GedmoTest\Collection;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use App\Entity\GedmoTest;
use App\Repository\GedmoTestRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

final class GetCollectionResolver implements QueryCollectionResolverInterface
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
     * @param iterable<GedmoTest> $collection
     * @param array<string, mixed> $context
     * @return iterable<GedmoTest>
     */
    public function __invoke(iterable $collection, array $context): iterable
    {
        $postData = $context["args"];

        try {
            $query = $this->gedmoTestRepository->createQueryBuilder("gt");

            if (array_key_exists("name", $postData)) {
                $query
                    ->where("gt.name LIKE :name")
                    ->setParameter("name", "%" . $postData["name"] . "%");
            }

            $paginator = new Paginator(
                new DoctrinePaginator(
                    (
                    $query
                        ->groupBy("gt.id")
                        ->setFirstResult(isset($postData["page"]) ? ($postData["page"] - 1) * (isset($postData["itemsPerPage"]) ? $postData["itemsPerPage"] : 100) : 0)
                        ->setMaxResults(isset($postData["itemsPerPage"]) ? $postData["itemsPerPage"] : 100)
                    )
                        ->getQuery()
                        ->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
                        ->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, $this->translator->getLocale())
                        ->setHint(\Gedmo\Translatable\TranslatableListener::HINT_FALLBACK, 1)
                )
            );
        } catch (\Exception $exception) {
            throw new BadRequestHttpException($this->translator->trans("recordsNotFound"));
        }

        return $paginator;
    }
}
