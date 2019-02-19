<?php
namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use AppBundle\Entity\Respondent;
use AppBundle\Entity\WellnessProfessional;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
/**
 * generates a user registration form and saves the user to the database
 *
 * @version 1.0
 * @author cst233
 */
class RegistrationController extends Controller
{
    /**
     *
     * Register action genereates forms for both login and register
     * Does validation for login credentials before accessing the database
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *

     * @Route("/register", name="user_registration")
     *
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        //Build the form
        $user = new User();
        $registrationform = $this->createForm(UserType::class, $user);


        //Handle the submission - on POST
        $registrationform->handleRequest($request);

        if ($registrationform->isSubmitted() && $registrationform->isValid() && ($registrationform['isWellPro']->getData() == false || !empty($registrationform['wellnessPro']['practiceName']->getData())))
        {
            //Encode the password
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $user->setUserId($this->getGUID());



            //Check if wellness pro box is checked. If not, make respondent
            $em = $this->getDoctrine()->getManager();

            if ($registrationform['isWellPro']->getData() == true)
            {
                //Make wellness pro and populate data with form info
                $wellpro = new WellnessProfessional();

                $wellpro->setUser($user);
                $wellpro->setPracticeName($registrationform['wellnessPro']['practiceName']->getData());
                $wellpro->setContactEmail($registrationform['wellnessPro']['contactEmail']->getData());
                $wellpro->setContactNumber($registrationform['wellnessPro']['contactNumber']->getData());
                $wellpro->setWebsite($registrationform['wellnessPro']['website']->getData());
                $em->persist($wellpro);

            }
            else
            {
                //Make respondent and link to user
                $resp = new Respondent();
                $resp->setUser($user);
                $em->persist($resp);
            }

            //Save the user

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('registration_confirmation');
        }
        else if ($registrationform['isWellPro']->getData() == true && !empty($registrationform['wellnessPro']['practiceName']->getData()))
        {
            $registrationform->addError(new FormError("Practice Name is required for a Wellness Professional."));
        }

        //Set the default action for the login form
        $action = "#";

        //Variables to track a user attempting to login
        $usernameGood = false;
        $passwordGood = false;
        $enteredUsername = null;
        $enteredPassword = null;

        //See if login form was posted and get username and password if they are set
        if(isset($_POST['_username'])){
            $enteredUsername = $_POST['_username'];
        }

        if(isset($_POST['_password'])){
            $enteredPassword = $_POST['_password'];
        }

        //If both are set validate the form
        if(isset($enteredUsername) && isset($enteredPassword))
        {
            //validate them
            $validator = Validation::createValidator();

            //Validate username
            $violations = $validator->validate($enteredUsername, array(
                new Length(array('min' => 3)),
                new Email,
            ));

            //If there are no errors with the username
            if(sizeof($violations) == 0)
            {
                $usernameGood = true;
            }

            //Validate password
            $violations = $validator->validate($enteredPassword, array(
                new Length(array('min' => 1)),

            ));

            //If there are no errors with the password
            if(sizeof($violations) == 0)
            {

                $passwordGood = true;
            }

            //if username and password good change action on the form
            if($usernameGood && $passwordGood)
            {
                $action = "login";
            }

        }

        //Create the login form
        $loginForm = $this->get("form.factory")->createNamed(null,'Symfony\\Component\\Form\\Extension\\Core\\Type\\FormType',null,array('action'=>$action));
        //Add fields to the form with values entered in
        $loginForm->add('_username',TextType::class, array('label'=>'Email','attr'=>array('value'=>$enteredUsername),'constraints' => array(new Length(array('min' => 3)), new Email)));
        $loginForm->add('_password',PasswordType::class, array('always_empty'=>false,'attr'=>array('value'=>$enteredPassword),'constraints' => new Length(array('min' => 1))));
        //See if there are errors passed to the controller
        if(isset($_GET['error']))
        {
            //If so, add the error message to the page
            $loginForm->get('_username')->addError(new FormError($_GET['error']));
        }

        //Handle the login request
        $loginForm->handleRequest($request);

        // if the form isnt submitted correctly, rerender the page
        return $this->render(
            'registration/register.html.twig',
            array('registrationForm' => $registrationform->createView(), 'loginForm' => $loginForm->createView())
            );
    }

    function getGUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid =
                substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);

            return $uuid;
        }
    }

}