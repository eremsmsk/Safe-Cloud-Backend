<?php

namespace App\Resolver\Group\Collection;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use App\Entity\Group;
use App\Repository\GroupRepository;
use Doctrine\ORM\Tools\Pagination\Paginator AS DoctrinePaginator;

final class GetCollectionResolver implements QueryCollectionResolverInterface
{
    /** @var GroupRepository */
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * @param iterable<Group> $collection
     * @param array<string, mixed> $context
     * @return iterable<Group>
     */
    public function __invoke(iterable $collection, array $context): iterable
    {
        $postData = $context["args"];

        $query = $this->groupRepository->createQueryBuilder("g");

        $paginator = new Paginator((
            new DoctrinePaginator((
                $query
                    ->groupBy("g.id")
                    ->setFirstResult(isset($postData["page"]) ? ($postData["page"] - 1) * (isset($postData["itemsPerPage"]) ? $postData["itemsPerPage"] : 100) : 0)
                    ->setMaxResults(isset($postData["itemsPerPage"]) ? $postData["itemsPerPage"] : 100)
            )->getQuery())
        ));

        return $paginator;
    }
}
