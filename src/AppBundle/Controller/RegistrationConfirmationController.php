<?php
namespace AppBundle\Controller;


use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\BrowserKit\Response;

/**
 * genereates user successfully created page
 * (will be deleted later)
 *
 * @version 1.0
 * @author cst233
 */
class RegistrationConfirmationController extends Controller
{
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @Route("/registration_confirmation", name="registration_confirmation")
     *
     */
    public function confirmAction(Request $request)
    {

        return $this->render(
            'registration/registration_confirm.html.twig');
    }
}