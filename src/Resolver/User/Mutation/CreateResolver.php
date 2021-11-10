<?php

namespace App\Resolver\User\Mutation;

use ApiPlatform\Core\GraphQl\Resolver\MutationResolverInterface;
use App\Entity\User;

final class CreateResolver implements MutationResolverInterface
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
        $postData = $context['args']['input'];

        if (!is_null($item)) {
            /** @var string $email */
            $email = str_replace("\"", "", $item->getEmail() ?? "");
            /** @var string $password */
            $password = str_replace("\"", "", $item->getPassword() ?? "");

            $item->setEmail($email);
            $item->setEmailCanonical($email);
            $item->setUsername($email);
            $item->setUsernameCanonical($email);
            $item->setPassword($password);
            $item->setPlainPassword($password);
            $item->setEnabled(true);
        }

        return $item;
    }
}