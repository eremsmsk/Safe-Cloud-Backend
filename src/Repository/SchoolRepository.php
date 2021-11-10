<?php

namespace App\Repository;

use App\Entity\School;
use App\Entity\User;
use App\Service\ImageProcessor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method School|null find($id, $lockMode = null, $lockVersion = null)
 * @method School|null findOneBy(array $criteria, array $orderBy = null)
 * @method School[]    findAll()
 * @method School[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, School::class);
    }


    /**
     * @param User $user
     * @return array
     */
    public function getSchoolInfo(User $user)
    {
        $result = ["success" => false, "data" => [], "message" => "No action taken", "errorMessage" => null, "userExist" => false];
        try {
            $school = $this->createQueryBuilder("s");
            $school
                ->select("s.id", "s.name", "s.image");
            $school
                ->where("s.id=:school")
                ->setParameter("school", $user->getSchool()->getId());
            $school = $school->getQuery()->getOneOrNullResult();

            $result = [
                "data" => $school,
                "success" => true,
                "message" => "success",
                "errorMessage" => null,
            ];
        } catch (\Exception $exception) {
            $result = [
                "data" => [],
                "success" => false,
                "message" => $exception->getMessage(),
                "errorMessage" => null,
            ];
        }
        return $result;
    }
    /**
     * @param User $user
     * @return array
     */
    public function newSchool(array $postData,ImageProcessor $imageProcessor,EntityManagerInterface $em)
    {
        $result = ["success" => false, "data" => [], "message" => "No action taken", "errorMessage" => null, "userExist" => false];
        try {
            $saveImage = null;
            if (isset($postData["file"]) && $postData["file"] != "") {
                $saveImage = $imageProcessor->saveImage($postData["file"]);
            }
            $school=new School();

            $school
                ->setName($postData["name"])
                ->setImage($saveImage);

            $em->persist($school);
            $em->flush();

            $result = [
                "data" => $school,
                "success" => true,
                "message" => "success",
                "errorMessage" => null,
            ];
        } catch (\Exception $exception) {
            $result = [
                "data" => [],
                "success" => false,
                "message" => $exception->getMessage(),
                "errorMessage" => null,
            ];
        }
        return $result;
    }

    // /**
    //  * @return School[] Returns an array of School objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?School
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
