<?php

// LogoutListener.php - Change the namespace according to the location of this class in your bundle
namespace App\EventListener;

use App\Entity\LoginLog;
use App\Entity\Sessions;
use App\Entity\User;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class SessionLogoutHandler implements LogoutHandlerInterface
{

    protected $userManager;
    protected $em;
    protected $sessionHandler;

    public function __construct(UserManagerInterface $userManager, EntityManagerInterface $entityManager, \SessionHandlerInterface $sessionHandler)
    {
        $this->userManager = $userManager;
        $this->em = $entityManager;
        $this->sessionHandler = $sessionHandler;
    }

    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        /**
         * @var User $user
         */
        $user = $token->getUser();
        if ($user == "anon."){
            return new RedirectResponse("/".$request->getLocale()."/admin");
        }

        $user
            ->setSessionId(null);
        $this->em->persist($user);
        $this->em->flush();

        $request->getSession()->invalidate();
    }
}
