<?php

namespace App\Controller\Health;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/healthz", name="healthz")
 */
class HealthController extends AbstractController
{
    /**
     * @Route("", name="healthz_index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->connect();
            $connected = $em->getConnection()->isConnected();
            if ($connected) {
                return new Response('Up');
            } else {
                return new Response('Down: database connection error', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch (\Exception $exception){
            return new Response('Down: database connection error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
