<?php

namespace App\Controller\Admin;

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
    public function test(Request $request)
    {
        if ($request->getMethod() == "POST"){
            return new Response("Test İçin Local 'de Kodu Değiştirip Deneyiniz...");
//            $image = $imageProcessor->saveImage($request->files->get("file"));
//            dump($image);
//            if (!is_null($image)){
//                sleep(10);
//                $delete = $imageProcessor->deleteImage($image);
//                dump($delete);
//            }
//            exit();
        }

        return $this->render("base.html.twig");
    }
}
