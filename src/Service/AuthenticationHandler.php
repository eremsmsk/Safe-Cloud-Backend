<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * Class AuthenticationHandler
 * @package AppBundle\Handler
 */
class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var Session
     */
    private $session;


    /**
     * AuthenticationHandler constructor.
     * @param RouterInterface $router
     * @param Session $session
     */
    public function __construct(RouterInterface $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return JsonResponse|RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(array('success' => true, 'message' => 'Login Successfully'));
        }else {
            $pathOrigin = $request->headers->get("origin").'/'.$request->getLocale();
            $url = $request->headers->get("referer");
            $pathUrl = substr($url,strlen($pathOrigin),strlen($url));
            if ($pathUrl == "/admin/login"){
                /** @var User|string $user */
                $user = $token->getUser();
                if ($user != "anon." && $user->hasRole("ROLE_CUSTOMER")){
                    $token->setAuthenticated(false);
                    $request->getSession()->set(Security::AUTHENTICATION_ERROR, "Kullanıcı Bulunamadı.");
                    return new RedirectResponse($this->router->generate('fos_user_security_login'));
                }
                return new RedirectResponse("/".$request->getLocale()."/admin");
            }else{
                return new RedirectResponse($request->headers->get("referer"));
            }
        }
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse|RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        dump($exception->getMessage());
        exit();
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(array('success' => false, 'message' => $exception->getMessage()));
        }else {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
            return new RedirectResponse($this->router->generate('fos_user_security_login'));
        }
    }
}
