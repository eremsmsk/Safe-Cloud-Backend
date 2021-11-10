<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     *
     */
    public function new(array $postData, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, User $actUser)
    {
        $result = ["success" => false, "message" => "No action taken", "errorMessage" => null, "userExist" => false];
        try {
            $user = new User();
            $userEncodePassword = $encoder->encodePassword($user, $postData['password']);
            $role = [];
            if (isset($postData["role"])) {
                if ($postData["role"] == 1) {
                    $role = ['ROLE_SUPER_ADMIN'];
                } else if ($postData["role"] == 2) {
                    $role = ['ROLE_ADMIN'];
                } else {
                    $role = ['ROLE_EMPLOYEE'];

                }
            }

            $user->setUsername($postData['mail'])
                ->setUsernameCanonical($postData['mail'])
                ->setEmail($postData['mail'])
                ->setEmailCanonical($postData['mail'])
                ->setPassword($userEncodePassword)
                ->setEnabled(1)
                ->setRoles($role);
            if (!is_null($actUser->getSchool())) {
                $user->setSchool($actUser->getSchool());
            }

            $em->persist($user);

            $em->flush();
            $result = [
                "data" => null,
                "success" => true,
                "message" => "success",
                "errorMessage" => null,
            ];
        } catch (\Exception $exception) {
            $pos = strpos($exception->getMessage(), "INSERT INTO");
            if ($pos) {
                $result["userExist"] = true;
            }
            $result = [
                "success" => false,
                "message" => $exception->getMessage(),
                "errorMessage" => null,
                "userExist" => false
            ];
        }

        return $result;
    }
}

