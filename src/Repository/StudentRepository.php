<?php

namespace App\Repository;

use App\Entity\Student;
use App\Entity\User;
use App\Service\ImageProcessor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }


    /**
     * @param array $postData
     * @param ImageProcessor $imageProcessor
     * @return array
     */
    public function newStudent(array $postData, ImageProcessor $imageProcessor, User $user)
    {
        $result = ["success" => false, "message" => "No action taken", "errorMessage" => null, "userExist" => false];
        $em = $this->getEntityManager();
        try {
            $saveImage = null;
            if (isset($postData["file"]) && $postData["file"] != "") {
                $saveImage = $imageProcessor->saveImage($postData["file"]);
            }
            $student = new Student();
            $student
                ->setName($postData["nameSurname"])
                ->setNo($postData["no"])
                ->setUser($user)
                ->setSchool($user->getSchool())
                ->setImage($saveImage);

            $em->persist($student);
            $em->flush();
            $result = [
                "success" => true,
                "message" => "Successfully",
                "errorMessage" => null,
                "userExist" => false,
                "data" => [
                    "id" => $student->getId(),
                    "name" => $student->getName(),
                    "image" => $student->getImage(),
                    "no" => $student->getNo(),
                ]
            ];
        } catch (\Exception $exception) {
            $pos = strpos($exception->getMessage(), "INSERT INTO");
            if ($pos) {
                $result["userExist"] = true;
            }
            $result["success"] = false;
            $result["message"] = $exception->getMessage();
            $result["errorMessage"] = null;

        }

        return $result;
    }


    /**
     * @param array $postData
     */
    public function getStudentList(array $postData, User $user)
    {
        $result = ["success" => false, "data" => [], "message" => "No action taken", "errorMessage" => null, "userExist" => false];
        $em = $this->getEntityManager();
        try {
            $students = $this->createQueryBuilder("s");
            $students
                ->select("s.id", "s.name", "s.image", "s.no");
            $students
                ->leftJoin("s.school", "sc");
            $students->where("sc.id=:school")
                ->setParameter("school", $user->getSchool()->getId())
            ;
            if (isset($postData["no"]) && $postData["no"] != "") {
                $students
                    ->where("s.no =:ogrNo")
                    ->setParameter("ogrNo", $postData["no"]);
            }
            $students = $students->getQuery()->getArrayResult();
            $result = [
                "data" => $students,
                "success" => true,
                "message" => "success",
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

}
