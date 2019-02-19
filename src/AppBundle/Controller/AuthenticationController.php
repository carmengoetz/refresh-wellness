<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Services\GetStats;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


/**
 *
 *
 * @version 1.0
 * @author
 * @Route("/authenticate", name="authenticate")
 */
class AuthenticationController extends Controller
{
    /**
     * Summary of login
     * @param Request $request
     * @Route("/login", name="login")
     */
    public function login(Request $request)
    {
        //gets the currently logged in user
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return new JsonResponse(array('status'=>'success', 'message'=>"You have logged into " . $user->getUsername(), 'data'=> array()
                                ));
    }

    /**
     * Summary of logout
     * @param Request $request
     * @Route("/logout")
     */
    public function logout( Request $request )
    {
        $this->get('security.token_storage')->setToken();
        return $this->redirectToRoute('user_registration');
    }

}