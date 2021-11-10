<?php

namespace App\Resolver\Group\Mutation;

use ApiPlatform\Core\GraphQl\Resolver\MutationResolverInterface;
use App\Entity\Group;
use Symfony\Component\Serializer\SerializerInterface;

final class CreateResolver implements MutationResolverInterface
{
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param Group|null $item
     * @param array<string, mixed> $context
     * @return Group|null
     */
    public function __invoke($item, array $context): ?Group
    {
        $postData = $context['args']['input'];

        if (!is_null($item)){
            $item
                ->setRoles(array_column(json_decode($this->serializer->serialize($item->getSubRole()->toArray() ?? [],"json"),true) ?? [],"id"));
        }

        return $item;
    }
}