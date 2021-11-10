<?php

namespace App\Repository;

use App\Entity\GedmoTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GedmoTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method GedmoTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method GedmoTest[]    findAll()
 * @method GedmoTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<GedmoTest>
 */
class GedmoTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GedmoTest::class);
    }
}
