<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Httpful\Httpful;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\User;
use AppBundle\Entity\Conversation;
use AppBundle\Services\ConvertToArray;

/**
 * Controller for handling conversations.
 *
 * @version 1.0
 * @author cst231
 * @Route("/conversation")
 * @Security("is_granted('skip')")
 */
class ConversationController extends Controller
{
    /**
     * Send a json array back with a list of conversations for the logged in user.
     * Only shows the specfied page's results.
     * @Route("/view/{pageNum}")
     * @param Request $request
     */
    public function populateConversations(Request $request, $pageNum)
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
            //Call repository to get a list of conversations
            $conversations = $this->getDoctrine()->getRepository(Conversation::class)->findByPageNum($user, $pageNum);

            //Only call service if there are actually any conversations
            if(count($conversations) > 0)
            {
                //Call service to convert list of conversations to the assoc array we want
                $arrayService = new ConvertToArray();
                //Set the data of the return array to be the array that comes back from the service
                $responseArray['data'] = $arrayService->convertToArrayConversations($user, $conversations);
            }

            //Set status to success
            $responseArray['status'] = "success";
        }
        else
        {
            //if the user isn't logged in
            $responseArray['message'] = "User is not logged in.";
        }
        //convert the array to JSON
        return new JsonResponse($responseArray);
    }


    /**
     * Simple function to render the view messages page
     * @Route("/viewconversations")
     */
    public function renderPage()
    {
        $userlogin = $this->get('security.token_storage')->getToken()->getUser();

        $usersWithRelationships = array();
        $self = "";

        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {

            $self = $userlogin->getUserId();
            //Get relationships from the user
            $relationshipsInit = $userlogin->relationshipsInitiated->getValues();
            $relationshipsReq = $userlogin->relationshipsRequested->getValues();

            $relationshipsarray = array_merge($relationshipsInit, $relationshipsReq);

            $usersWithRelationships = array();

            foreach ($relationshipsarray as $rel)
            {
                if ( $rel->getUserIdOne()->getUserId() != $userlogin->getUserId())
                {
                    if (!array_key_exists($rel->getUserIdOne()->getUserId(),$usersWithRelationships))
                    {
                        $usersWithRelationships[$rel->getUserIdOne()->getUserId()] = array("guid" => $rel->getUserIdOne()->getUserId(), "fullname" => $rel->getUserIdOne()->getName() );
                    }

                }
                else
                {
                    if (!array_key_exists($rel->getUserIdOne()->getUserId(),$usersWithRelationships))
                    {
                        $usersWithRelationships[$rel->getUserIdTwo()->getUserId()] = array("guid" => $rel->getUserIdTwo()->getUserId(), "fullname" => $rel->getUserIdTwo()->getName() );
                    }
                }


            }

            usort($usersWithRelationships, function($a, $b) {
                if ($a["fullname"] > $b["fullname"])
                {
                    return 1;
                }
                else
                {
                    return -1;
                }
            });


        }

        $params = array("relationships" => $usersWithRelationships, "self" => $self);
        return $this->render("messages/view_conversations.html.twig", array("params" => $params));
    }

}