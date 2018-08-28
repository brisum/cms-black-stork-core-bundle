<?php

namespace Brisum\Stork\Bundle\CoreBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="_login")
     *
     * @param Request $request
     * @return Response
     * @throw ORMException
     */
    public function loginAction(Request $request)
    {
        /** @var AuthenticationUtils $authUtils */
        $authUtils = $authUtils = $this->container->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render(
            '@StorkCoreBundle/Auth/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]
        );
    }

    /**
     * @Route("/login_check", name="_login_check")
     *
     * @param Request $request
     * @return Response
     * @throw ORMException
     */
    public function loginCheckAction(Request $request)
    {
        //
    }

    /**
     * @Route("/logout", name="_logout")
     *
     * @param Request $request
     * @return Response
     * @throw ORMException
     */
    public function logoutAction(Request $request)
    {
        //
    }
}
