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
use AppBundle\Repository\UserRepository;
use AppBundle\Services\ConvertToArray;

/**
 * Controller for searching for users and groups
 *
 * @version 1.0
 * @author cst213
 * @Route("/search")
 * @Security("is_granted('skip')")
 */
class SearchController extends Controller
{

    /**
     * controller to search for users (respondents or wellness professionals)
     *
     * @Route("/{pageNum}/{criteria}")
     * @param Request $request
     * @param mixed $criteria - The search criteria
     * @param mixed $pageNum - Pagination
     * @return JsonResponse - results of the search
     */
    public function searchUsers(Request $request, $criteria, $pageNum)
    {
        //Create the array or results to JSON encode
        $responseArray = array();

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
            //Query the database and create an array of returned user objects
            $repo = $this->getDoctrine()->getRepository(User::class);

            //if the string passed in contains a space (indicating firstname lastname)
            if (strpos($criteria, ' '))
            {
                //split the name up by the space into an array of names
                $criterion = explode(' ', $criteria);
                //search the repository based on the name
                $users = $repo->search($criterion, $pageNum);
            }
            //if the string passed in doesnt have a space
            else
            {
                //searc hthe repository based on the stribng
            	$users = $repo->search(array($criteria), $pageNum);
            }


            //Only call service if there are actually any messages
            if(count($users) > 0)
            {
                //Call service to convert list of conversations to the assoc array we want
                $arrayService = new ConvertToArray();

                //default if it is on the first or last pages to 0 (false)
                $responseArray['data']['firstPage'] = 0;
                $responseArray['data']['lastPage'] = 0;

                //if the pageNum is 1 set firstPage to 1 (true)
                if ($pageNum == 1)
                {
                    $responseArray['data']['firstPage'] = 1;
                }

                //if the amount of users is less than 20, set lastPage to 1 (true)
                if (count($users) < 20)
                {
                	$responseArray['data']['lastPage'] = 1;
                }

                //Set the data of the return array to be the array that comes back from the service
                $responseArray['data']['objects'] = $arrayService->convertToArraySearch($user, $users);

                //Set status to success
                $responseArray['status'] = "success";
            }
            //if there were no users returned
            else 
            {
                $responseArray['message'] = "Your search yielded no results.";
            }
        }
        //if user is not logged in
        else{
            $responseArray['message'] = "User is not logged in.";
        }

        return new JsonResponse($responseArray);
    }

    /**
     * Simple function to render the results page of the search
     * @Route("/")
     */
    public function renderPage()
    {
        //render the search results page
        return $this->render("search/view_search_results.html.twig", array('params'=> array()));

    }

}