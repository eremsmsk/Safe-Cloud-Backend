<?php

namespace App\Controller\Api;

use App\Repository\SchoolRepository;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;
use App\Repository\VacationLogRepository;
use App\Repository\VacationsRepository;
use App\Repository\VacationTypeRepository;
use App\Service\ImageProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/api/mobile-function", name="mobile_function", methods={"POST"})
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/new-student", name="new_student", methods={"POST"})
     * @param Request $request
     * @param StudentRepository $studentRepository
     * @param ImageProcessor $imageProcessor
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newStudent(Request $request,StudentRepository $studentRepository,ImageProcessor $imageProcessor,UserInterface $user)
    {
        $language = $request->getLocale();
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData = array_merge($postData, $request->request->all());
        $postData = array_merge($postData, $request->files->all());
        $student=$studentRepository->newStudent($postData,$imageProcessor,$user);

        return $this->json($student);
    }

    /**
     * @Route("/get-student-list", name="get_student_list", methods={"POST"})
     * @param Request $request
     * @param StudentRepository $studentRepository
     * @param ImageProcessor $imageProcessor
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getStudentList(Request $request,StudentRepository $studentRepository,ImageProcessor $imageProcessor,UserInterface $user)
    {
        $language = $request->getLocale();
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData = array_merge($postData, $request->request->all());
        $postData = array_merge($postData, $request->files->all());
        $student=$studentRepository->getStudentList($postData,$user);

        return $this->json($student);
    }


    /**
     * @Route("/new-vacation", name="new_vacation", methods={"POST"})
     * @param Request $request
     * @param StudentRepository $studentRepository
     * @param ImageProcessor $imageProcessor
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newVacation(Request $request,VacationsRepository $vacationsRepository,UserInterface $user)
    {
        $language = $request->getLocale();
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData = array_merge($postData, $request->request->all());
        $postData = array_merge($postData, $request->files->all());
        $student=$vacationsRepository->newVacation($postData,$user);

        return $this->json($student);
    }

    /**
     * @Route("/get-vacations", name="get_vacations", methods={"POST"})
     * @param Request $request
     * @param StudentRepository $studentRepository
     * @param ImageProcessor $imageProcessor
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getVacations(Request $request,VacationsRepository $vacationsRepository,EntityManagerInterface $em,UserInterface $user)
    {
        $language = $request->getLocale();
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData = array_merge($postData, $request->request->all());
        $postData = array_merge($postData, $request->files->all());
        $student=$vacationsRepository->getVacations($postData,$em,$user);

        return $this->json($student);
    }

    /**
     * @Route("/remove-vacation", name="remove_vacations", methods={"POST"})
     * @param Request $request
     * @param StudentRepository $studentRepository
     * @param ImageProcessor $imageProcessor
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeVacations(Request $request,VacationsRepository $vacationsRepository,EntityManagerInterface $em)
    {
        $language = $request->getLocale();
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData = array_merge($postData, $request->request->all());
        $postData = array_merge($postData, $request->files->all());
        $student=$vacationsRepository->removeVacation($postData,$em);
        return $this->json($student);
    }


    /**
     * @Route("/get-vacation-types", name="get_vacation_types", methods={"POST"})
     * @param Request $request
     * @param StudentRepository $studentRepository
     * @param ImageProcessor $imageProcessor
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getVacationTypes(Request $request,VacationTypeRepository $vacationTypeRepository)
    {
        $language = $request->getLocale();
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData = array_merge($postData, $request->request->all());
        $postData = array_merge($postData, $request->files->all());
        $vacationType=$vacationTypeRepository->getVacationTypes();

        return $this->json($vacationType);
    }

     /**
     * @Route("/check-vacation", name="check_vacation", methods={"POST"})
     * @param Request $request
     * @param StudentRepository $studentRepository
     * @param ImageProcessor $imageProcessor
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function checkVacation(Request $request,VacationsRepository $vacationsRepository,VacationLogRepository $vacationLogRepository,UserInterface $user)
    {
        $language = $request->getLocale();
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData = array_merge($postData, $request->request->all());
        $postData = array_merge($postData, $request->files->all());
        $student=$vacationsRepository->checkVacation($postData,$vacationLogRepository,$user);

        return $this->json($student);
    }

     /**
     * @Route("/new-user", name="new-user", methods={"POST"})
     * @param Request $request
     * @param StudentRepository $studentRepository
     * @param ImageProcessor $imageProcessor
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newUser(Request $request,UserRepository $userRepository,EntityManagerInterface $em, UserPasswordEncoderInterface $encoder,UserInterface $user)
    {
        $language = $request->getLocale();
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData = array_merge($postData, $request->request->all());
        $postData = array_merge($postData, $request->files->all());
        $student=$userRepository->new($postData,$em,$encoder,$user);

        return $this->json($student);
    }

    /**
     * @Route("/trigger-vacation", name="trigger_vacation", methods={"POST"})
     * @param Request $request
     * @param VacationsRepository $vacationsRepository
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function triggerVacation(Request $request,VacationsRepository $vacationsRepository,EntityManagerInterface $em)
    {
        $language = $request->getLocale();
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData = array_merge($postData, $request->request->all());
        $postData = array_merge($postData, $request->files->all());
        $student=$vacationsRepository->triggerVacation($postData,$em);

        return $this->json($student);
    }

    /**
     * @Route("/get-school-info", name="get_school_info", methods={"POST"})
     * @param Request $request
     * @param SchoolRepository $schoolRepository
     * @param EntityManagerInterface $em
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getSchoolInfo(Request $request,SchoolRepository $schoolRepository,EntityManagerInterface $em,UserInterface $user)
    {
        $language = $request->getLocale();
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData = array_merge($postData, $request->request->all());
        $postData = array_merge($postData, $request->files->all());
        $school=$schoolRepository->getSchoolInfo($user);

        return $this->json($school);
    }

    /**
     *  @Route("/new-school", name="new_school", methods={"POST"})
     * @param Request $request
     * @param SchoolRepository $schoolRepository
     * @param ImageProcessor $imageProcessor
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newSchool(Request $request,SchoolRepository $schoolRepository,ImageProcessor $imageProcessor,EntityManagerInterface $em)
    {
        $language = $request->getLocale();
        $postData = [];
        $jsonData = json_decode($request->getContent(), true);
        if (!is_null($jsonData)) $postData = $jsonData;
        $postData = array_merge($postData, $request->query->all());
        $postData = array_merge($postData, $request->request->all());
        $postData = array_merge($postData, $request->files->all());
        $school=$schoolRepository->newSchool($postData,$imageProcessor,$em);

        return $this->json($school);
    }






}
