<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Respondent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use AppBundle\Entity\Wellness;
use Symfony\Component\HttpFoundation\Request;
use Httpful\Httpful;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use AppBundle\Services\ViewWellnessQuestions;

/**
 * generates the wellness entity for the user to answer questions and send the
 * answers to the stored in the database
 *
 * @version 1.0
 * @author cst245
 * @Route("/howareyoufeeling", name="update_wellness")
 */
class WellnessController extends Controller
{
    /**
     * Generates and returns JSON properties/objects to the page that indicates
     * which inputs should be on the page, and what text content to show on the page.
     * @Route("/view", name="view_wellness")
     */
    public function viewQuestion(Request $request)
    {
        $responseArray = array();

        $userLoggedIn = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {

            $viewWellnessQuestions = new ViewWellnessQuestions();
            $generatedArray = $viewWellnessQuestions->generateArray($userLoggedIn);

            //Return the array that was created as JSON
            return new JsonResponse($generatedArray);

        }else{
            $responseArray = array(
                'status'=>"failure",
                'message'=>"User is not logged in.",
                'data'=> array(
                )
            );
        }

        return new JsonResponse($responseArray);
    }

    /**
     * takes in the information that has been passed in with a json object
     * so that it van be stored in the database for the user
     *
     * @param $request
     * @Route("/submitanswers/{json}", name="submit_answers")
     */
    public function updateWellness(Request $request, $json)
    {

        $em = $this
            ->get('doctrine')
            ->getManager();

        $responseArray = array();

        $setUser = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {

            //if the user has already answered wellness questions
            if (!$securityContext->isGranted('skip'))
            {
                $setRespondent = false;

                //if the user does exist in the DB
                if($setUser)
                {
                    //change repo to look in respondent table
                    $repo  = $em->getRepository(Respondent::class);
                    //check to ensure that the logged in user has a role of respondent
                    $setRespondent = $repo->findOneBy(['user' => $setUser->userID]);
                }

                //set up string to add to message that will be returned & id variable
                //to set id passed back & status property
                $id = "";
                $status = "failure";
                $errorsString = "";

                if($setRespondent && $setUser)
                {
                    //decode the json that has been passed in the URL params into a php object
                    $wellnessObj = json_decode($json);

                    //create a new wellness object
                    $wellness = new Wellness();

                    //set the values to be entered into the db
                    $wellness->setRespondent($setRespondent);

                    //set mood, energy, sleep, thoughts, based on what passed in
                    $wellness->setMood($wellnessObj->mood);
                    $wellness->setEnergy($wellnessObj->energy);
                    $wellness->setSleep($wellnessObj->sleep);
                    $wellness->setThoughts($wellnessObj->thoughts);

                    //set date to current date with format yyyy-mm-dd
                    $wellness->setDate(date("Y-m-d"));

                    //validate the wellness object
                    $validator = $this->get('validator');
                    $errors = $validator->validate($wellness);

                    //if there are errors
                    if (count($errors) > 0) {
                        //turn it into a string
                        $errorsString = $errors->get(0)->getMessage();

                    }
                    //if there aren't any errors
                    else
                    {
                        $em = $this->getDoctrine()->getManager();

                        //set success of adding to db to true by deafult;
                        $success = true;

                        try
                        {
                            //tells Doctrine you want to eventually save the Wellness object (but no queries yet)
                            $em->persist($wellness);
                            //actually executes the queries
                            $em->flush();

                        }
                        catch(\Exception $e)
                        {
                            //set success to false if there is an issue adding to the db
                            $success = false;
                        }

                        //if the record was added successfully
                        if($success)
                        {
                            //get the id of the record we just inserted
                            $id = $em->getConnection()->lastInsertId();
                            //set the status to success
                            $status = "success";
                        }
                    }

                }
                //if the user is not logged in or isn't a respondent
                else
                {
                    $status = "failure";
                    $errorsString = "user is not logged in or is not a respondent";
                }

                //create a responce with the status, the messages to send back, and the wellnessid
                $response = array("status" => $status, "message" => $errorsString, "wellnessId"=>$id);

                //return the response as a JSON object
                return new JsonResponse($response);
            }
            else{
                $responseArray = array(
                'status'=>"failure",
                'message'=>"User has not answered wellness questions today",
                'data'=> array(
                )
            );
            }

        }else{
            $responseArray = array(
                'status'=>"failure",
                'message'=>"User is not logged in.",
                'data'=> array(
                )
            );
        }

        return new JsonResponse($responseArray);

    }

    /**
     * Simple function to render the view wellness questions page
     * @Route("/")
     *
     */
    public function renderWellness()
    {
        $userLogin = $this->get('security.token_storage')->getToken()->getUser();

        //Get logged in user
        $securityContext = $this->container->get('security.authorization_checker');

        //if the user isn't logged in, redirect to the login page
        if (!$securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirect('../authenticate/login');
        }
        //if the user has already answered wellness questions
        elseif ($securityContext->isGranted('skip'))
        {
            return $this->redirect('../profile');
        }

        return $this->render("wellness/howareyoufeeling.html.twig", array('params' => array()));



    }

}
