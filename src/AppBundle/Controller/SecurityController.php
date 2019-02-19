<?php

/**
 * Class to deal with login actions.
 *
 * @version 1.0
 * @author cst233
 */
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * Symfony checks the login action in security.yml to see if credentials are passed to this page.
     * This function is only called if the credentials entered to the page are invalid.
     *
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        return $this->redirectToRoute('user_registration', array('error' => 'Invalid Credentials'));
    }
}