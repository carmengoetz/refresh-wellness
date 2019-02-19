<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Httpful\Httpful;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\User;
use AppBundle\Entity\Message;
use AppBundle\Services\Messaging;
use AppBundle\Services\ConvertToArray;

/**
 * Controller sending and receiving messages between users
 *
 * @version 1.0
 * @author cst213
 * @Route("/message")
 * @Security("is_granted('skip')")
 */
class MessageController extends Controller
{
    /**
     * send a message
     * @Route("/send/{json}")
     * @param Request $request
     */
    public function sendMessage(Request $request, $json)
    {
        $responseArray = array();

        //$em = null;

        $em = $this
           ->get('doctrine')
           ->getManager();

        $validator = $this->get('validator');

        //Set up return array
        $responseArray = array(
            'status'=>"failure",
            'message'=>"",
            'data'=> array(
            )
        );

        //User that is logged in currently
        $user = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {

            //Check to see if passed in object is a JSON object
            $responseArray['message'] = "Invalid message format.";

            //create new messaging object
            $message = new Messaging($em, $validator);

            //call the sendMessage function from the service
            $responseArray = $message->sendMessage($json, $user);

        }else{
            $responseArray['message'] = "User is not logged in.";
        }

        return new JsonResponse($responseArray);
    }

    /**
     * Send a json array back with a list of messages between the logged in user and the user passed in.
     * Only shows the specfied page's results.
     * @Route("/view/{userID}/{pageNum}")
     * @param Request $request
     * @ParamConverter("otherUser", class="AppBundle:User")
     */
    public function populateMessages(Request $request, User $otherUser = null, $pageNum)
    {
        //Set up return array
        $responseArray = array(
            'status'=>"failure",
            'message'=>"",
            'data'=> array(
            )
        );

        //User that is logged in currently
        $user = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            //Call repository to get a list of messages
            $messages = $this->getDoctrine()->getRepository(Message::class)->findByPageNum($user, $otherUser, $pageNum);

            //Only call service if there are actually any messages
            if(count($messages) > 0)
            {
                //Call service to convert list of conversations to the assoc array we want
                $arrayService = new ConvertToArray();
                //Set the data of the return array to be the array that comes back from the service
                $responseArray['data'] = $arrayService->convertToArrayMessages($user, $otherUser, $messages);
            }

            //Set status to success
            $responseArray['status'] = "success";
        }
        else
        {
            $responseArray['message'] = "User is not logged in.";
        }

        return new JsonResponse($responseArray);
    }


    /**
     * Simple function to render the view messages page
     * @Route("/viewmessages")
     */
    public function renderPage()
    {
        $userlogin = $this->get('security.token_storage')->getToken()->getUser();


        return $this->render("messages/view_messages.html.twig", array("selfGuid"=> $userlogin->getUserId()));
    }

}