<?php

namespace App\Repository;

use App\Entity\Student;
use App\Entity\User;
use App\Entity\VacationLog;
use App\Entity\Vacations;
use App\Entity\VacationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Vacations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vacations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vacations[]    findAll()
 * @method Vacations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vacations::class);
    }


    /**
     * @param array $postData
     * @param User $user
     * @return array
     */
    public function newVacation(array $postData, User $user)
    {
        $result = ["success" => false, "message" => "No action taken", "errorMessage" => null, "userExist" => false];
        $em = $this->getEntityManager();
        try {
            $student = $em->getRepository(Student::class)->findOneBy(["no" => $postData["student"]]);
            $vacationType = $em->getRepository(VacationType::class)->findOneBy(["id" => $postData["type"]]);
            if (isset($student)) {
                if (isset($vacationType)) {
                    $vacation = new Vacations();
                    $vacation
                        ->setStudent($student)
                        ->setVacationType($vacationType)
                        ->setSchool($user->getSchool())
                        ->setUser($user);

                    if ($vacationType->getId() == 2) {
                        if (isset($postData["nowDateTime"])){
                            $date=(new \DateTime($postData["nowDateTime"]));
                            $endTime=(new \DateTime($postData["nowDateTime"]));
                        }else{
                            $date=(new \DateTime());
                            $endTime=$date;
                        }
                        $vacation
                            ->setStartDate($date)
                            ->setEndDate($date)
                            ->setStartTime($date)
                            ->setEndTime($endTime->modify("+15 minute"));
                    } else {
                        $vacation
                            ->setStartDate((new \DateTime($postData["startDate"])))
                            ->setEndDate((new \DateTime($postData["endDate"])))
                            ->setStartTime((new \DateTime($postData["startTime"])))
                            ->setEndTime((new \DateTime($postData["endTime"])));
                    }
                    $vacation->setBarcode("")
                        ->setEnabled(1);
                    $em->persist($vacation);
                    $em->flush();

                    $vacation
                        ->setBarcode(md5($vacation->getId()));
                    $em->persist($vacation);
                    $em->flush();

                } else {
                    $result = [
                        "success" => false,
                        "message" => "vacationTypeNotFound",
                        "errorMessage" => "vacationTypeNotFound",
                    ];
                    return $result;
                }
            } else {
                $result = [
                    "success" => false,
                    "message" => "studentNotFound",
                    "errorMessage" => "studentNotFound",
                ];
                return $result;
            }

            $result = [
                "data" => $vacation,
                "barcode" => $vacation->getBarcode(),
                "vacationId" => $vacation->getId(),
                "success" => true,
                "message" => "success",
            ];
        } catch (\Exception $exception) {
            $result = [
                "success" => false,
                "message" => $exception->getMessage(),
                "errorMessage" => null,
            ];
        }

        return $result;
    }

    /**
     * @param array $postData
     * @return array
     */
    public function getVacations(array $postData, EntityManagerInterface $em,User $user)
    {
        $result = ["success" => false, "message" => "No action taken", "errorMessage" => null, "userExist" => false];
        try {
            $vacations = $this->createQueryBuilder("v");
            $vacations
                ->select("v.startDate", "v.endDate", "v.startTime", "v.endTime", "v.id","v.enabled")
                ->addSelect("u.username")
                ->addSelect("vt.name as vacationType")
                ->addSelect("s.name as studentName", "s.no", "s.image")
                ->addSelect("vl.date", "vl.time")
                ->leftJoin("v.user", "u")
                ->leftJoin("v.student", "s")
                ->leftJoin("v.vacationType", "vt")
                ->leftJoin("v.school", "sc")
                ->leftJoin("v.vacationLogs", "vl");
            $vacations
                ->where("sc.id=:school")
                ->setParameter("school", $user->getSchool()->getId());
            if (isset($postData["no"]) && $postData["no"]!=""){
                $vacations
                    ->where("s.no =:stdNo")
                    ->setParameter("stdNo",$postData["no"]);
            }
            $vacations = $vacations->getQuery()->getArrayResult();
            $result = [
                "data" => $vacations,
                "success" => true,
                "message" => "success",
            ];
        } catch (\Exception $exception) {
            $result = [
                "success" => false,
                "message" => $exception->getMessage(),
                "errorMessage" => null,
            ];
        }

        return $result;
    }

    /**
     * @param array $postData
     * @param EntityManagerInterface $em
     * @return array
     */
    public function removeVacation(array $postData, EntityManagerInterface $em)
    {
        $result = ["success" => false, "message" => "No action taken", "errorMessage" => null, "userExist" => false];
        try {
            $vacation = $this->find($postData["vacationId"]);
            if (!is_null($vacation)) {
                $em->remove($vacation);
                $em->flush();
            }

            $result = [
                "data" => null,
                "success" => true,
                "message" => "success",
            ];
        } catch (\Exception $exception) {
            $result = [
                "success" => false,
                "message" => $exception->getMessage(),
                "errorMessage" => null,
            ];
        }

        return $result;
    }

    /**
     * @param array $postData
     * @param EntityManagerInterface $em
     * @return array
     */
    public function triggerVacation(array $postData, EntityManagerInterface $em)
    {
        $result = ["success" => false, "message" => "No action taken", "errorMessage" => null, "userExist" => false];
        try {
            $vacation = $this->find($postData["vacationId"]);
            if (!is_null($vacation)) {
                if (isset($postData["enabled"])) {
                    $vacation->setEnabled($postData["enabled"] == 1 ? true : false);
                    $em->persist($vacation);
                    $em->flush();
                }
            }

            $result = [
                "data" => null,
                "success" => true,
                "message" => "success",
            ];
        } catch (\Exception $exception) {
            $result = [
                "success" => false,
                "message" => $exception->getMessage(),
                "errorMessage" => null,
            ];
        }

        return $result;
    }


    /**
     * @param array $postData
     * @param VacationLogRepository $vacationLogRepository
     * @param User $user
     * @return array
     */
    public function checkVacation(array $postData, VacationLogRepository $vacationLogRepository,User $user)
    {
        $result = ["success" => false, "message" => "No action taken", "errorMessage" => null, "userExist" => false];
        $em = $this->getEntityManager();
        try {
            $beforeDay = ((new \DateTime())->modify('monday this week')->format("Y-m-d H:i:s"));
            $date = (new \DateTime($postData["datetime"]))->format("Y-m-d");
            $time = (new \DateTime($postData["datetime"]))->format("H:i:s");
            if (isset($postData["barcode"]) && !is_null($postData["barcode"]) && $postData["barcode"] != "") {
                $vacation = $this->findOneBy(["barcode" => $postData["barcode"]]);
            }
            if (isset($postData["vacationId"]) && !is_null($postData["vacationId"]) && $postData["vacationId"] != "") {
                $vacation = $this->find((int)$postData["vacationId"]);
            }
            if (!is_null($vacation)) {
                $vacationLog = $em->getRepository(VacationLog::class)->createQueryBuilder("vl");
                $vacationLog->leftJoin("vl.school","sc");
                    $vacationLog->where("vl.vacation=:vacation")
                    ->setParameter("vacation", $vacation->getId())
                    ->andWhere("vl.date=:date")
                    ->setParameter("date", (new \DateTime($postData["datetime"]))->format("Y-m-d"))
                    ->andWhere("sc.id=:school")
                    ->setParameter("school", $user->getSchool()->getId());
                $vacationLog = $vacationLog->getQuery()
                    ->getOneOrNullResult();
                if (!is_null($vacationLog)) {
                    $result = [
                        "success" => false,
                        "message" => "vacationAlreadyUsed",
                        "errorMessage" => "vacationAlreadyUsed",
                    ];
                    return $result;
                }
                $vacation = $this->createQueryBuilder("v");
                if (isset($postData["barcode"]) && !is_null($postData["barcode"]) && $postData["barcode"] != "") {
                    $vacation->where("v.barcode=:barcode")
                        ->setParameter("barcode", $postData["barcode"]);
                }
                if (isset($postData["vacationId"]) && !is_null($postData["vacationId"]) && $postData["vacationId"] != "") {
                    $vacation->where("v.id=:id")
                        ->setParameter("id", $postData["vacationId"]);
                }

                $vacation
                    ->andWhere("v.startDate <=:startDate")
                    ->setParameter("startDate", $date)
                    ->andWhere("v.endDate >=:endDate")
                    ->setParameter("endDate", $date)
                    ->andWhere("v.startTime <=:startTime")
                    ->setParameter("startTime", $time)
                    ->andWhere("v.endTime >=:endTime")
                    ->setParameter("endTime", $time)
                    ->andWhere("v.enabled=1");
                $vacation = $vacation
                    ->getQuery()->getOneOrNullResult();

                if (!isset($vacation)) {
                    $result = [
                        "success" => false,
                        "message" => "vacationNotFound",
                        "errorMessage" => "vacationNotFound",
                    ];
                    return $result;
                } else {
                    $vacationLog = $vacationLogRepository->newVacationLog($vacation,$user);
                }
                /**@param Vacations $vacation */
                $vacationLog["image"] = $vacation->getStudent()->getImage();
                $vacationLog["studentName"]=$vacation->getStudent()->getName();
                $result = $vacationLog;
            } else {
                $result = [
                    "success" => false,
                    "message" => "vacationNotFound",
                    "errorMessage" => "vacationNotFound",
                ];
                return $result;
            }

        } catch (\Exception $exception) {
            $result = [
                "success" => false,
                "message" => $exception->getMessage(),
                "errorMessage" => null,
            ];
        }

        return $result;
    }
}
