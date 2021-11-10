<?php

namespace App\Resolver\Role\Collection;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use App\Entity\Role;
use App\Repository\RoleRepository;
use Doctrine\ORM\Tools\Pagination\Paginator AS DoctrinePaginator;

final class GetCollectionResolver implements QueryCollectionResolverInterface
{
    /** @var RoleRepository */
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param iterable<Role> $collection
     * @param array<string, mixed> $context
     * @return iterable<Role>
     */
    public function __invoke(iterable $collection, array $context): iterable
    {
        $postData = $context["args"];

        $query = $this->roleRepository->createQueryBuilder("r");

        $paginator = new Paginator((
            new DoctrinePaginator((
                $query
                    ->groupBy("r.id")
                    ->setFirstResult(isset($postData["page"]) ? ($postData["page"] - 1) * (isset($postData["itemsPerPage"]) ? $postData["itemsPerPage"] : 100) : 0)
                    ->setMaxResults(isset($postData["itemsPerPage"]) ? $postData["itemsPerPage"] : 100)
            )->getQuery())
        ));

        return $paginator;
    }
}
