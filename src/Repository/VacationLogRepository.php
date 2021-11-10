<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\VacationLog;
use App\Entity\Vacations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VacationLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacationLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacationLog[]    findAll()
 * @method VacationLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacationLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VacationLog::class);
    }


    /**
     * @param Vacations|null $vacations
     * @return array
     */
    public function newVacationLog(Vacations $vacations=null,User $user)
    {
        $result = ["success" => false, "message" => "No action taken", "errorMessage" => null,"userExist"=>false];
        $em = $this->getEntityManager();
        try {
            if (!is_null($vacations)){
                $vacationLog=new VacationLog();
                $vacationLog
                    ->setDate((new \DateTime()))
                    ->setTime((new \DateTime()))
                    ->setSchool($user->getSchool())
                    ->setVacation($vacations);
                $em->persist($vacationLog);
                $em->flush();

            }else{
                $result = [
                    "success" => false,
                    "message" => "vacationNotFound",
                    "errorMessage" => null,
                ];
                return $result;
            }
            $result = [
                "data"=>$vacationLog,
                "success" => true,
                "message" => "success",
            ];
        }catch (\Exception $exception){
            $result = [
                "success" => false,
                "message" => $exception->getMessage(),
                "errorMessage" => null,
            ];
        }

        return $result;
    }

}
