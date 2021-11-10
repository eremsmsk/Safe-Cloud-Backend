<?php

namespace App\Resolver\User\Item;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use App\Entity\User;

final class GetItemResolver implements QueryItemResolverInterface
{
    public function __construct()
    {
    }

    /**
     * @param User|null $item
     * @param array<string, mixed> $context
     * @return User|null
     */
    public function __invoke($item, array $context): ?User
    {
        $postData = $context["args"];

        return $item;
    }
}