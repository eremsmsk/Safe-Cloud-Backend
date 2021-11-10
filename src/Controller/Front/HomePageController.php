<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home_page", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        return $this->render("base.html.twig");
    }
//    /**
//     * @Route("/", name="home_page", methods={"GET","POST"})
//     * @param Request $request
//     * @return Response
//     */
//    public function index(Request $request)
//    {
//
//        return $this->render("base.html.twig");
//    }
}
