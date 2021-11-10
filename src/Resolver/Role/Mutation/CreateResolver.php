<?php

namespace App\Resolver\Role\Mutation;

use ApiPlatform\Core\GraphQl\Resolver\MutationResolverInterface;
use App\Entity\Role;

final class CreateResolver implements MutationResolverInterface
{
    public function __construct()
    {
    }

    /**
     * @param Role|null $item
     * @param array<string, mixed> $context
     * @return Role|null
     */
    public function __invoke($item, array $context): ?Role
    {
        $postData = $context['args']['input'];

        if (!is_null($item)){
            $item
                ->setId($postData["role"]);
        }

        return $item;
    }
}