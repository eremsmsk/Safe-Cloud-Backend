<?php


namespace App\Resolver\User\Collection;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\Tools\Pagination\Paginator AS DoctrinePaginator;

final class GetCollectionResolver implements QueryCollectionResolverInterface
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param iterable<User> $collection
     * @param array<string, mixed> $context
     * @return iterable<User>
     */
    public function __invoke(iterable $collection, array $context): iterable
    {
        $postData = $context["args"];

        $query = $this->userRepository->createQueryBuilder("u");

        $paginator = new Paginator((
            new DoctrinePaginator((
                $query
                    ->groupBy("u.id")
                    ->setFirstResult(isset($postData["page"]) ? ($postData["page"] - 1) * (isset($postData["itemsPerPage"]) ? $postData["itemsPerPage"] : 100) : 0)
                    ->setMaxResults(isset($postData["itemsPerPage"]) ? $postData["itemsPerPage"] : 100)
            )->getQuery())
        ));

        return $paginator;
    }
}
