<?php

namespace App\Repository;


use App\Entity\Sessions;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;

/**
 * @method Sessions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sessions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sessions[]    findAll()
 * @method Sessions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sessions::class);
    }

    /**
     * @param User $user
     * @param \SessionHandlerInterface $sessionHandler
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeSession(User $user, \SessionHandlerInterface $sessionHandler)
    {
        $em = $this->getEntityManager();

        if (!is_null($user) && !is_null($user->getSessionId())){
            $session = $em->getRepository(Sessions::class)->findOneBy(["sessId"=>$user->getSessionId()]);
            if (!is_null($session)){
                $em->remove($session);
                $em->flush();
                $session2 = $em->getRepository(Sessions::class)->findOneBy(["sessId"=>$user->getSessionId()]);
                if (!is_null($session2)){
                    $em->remove($session2);
                    $em->flush();

                    $user->setSessionId(null);
                    $em->persist($user);
                    $em->flush();
                }else{
                    $user->setSessionId(null);
                    $em->persist($user);
                    $em->flush();
                }

                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

}
