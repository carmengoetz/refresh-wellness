<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Services\GetStats;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Controller class for returning JSON responses to the page with 'success' or 'failures'
 * and an error message if appropriate. Also returns the wellness records for the given caregivee
 * if they have any stats.
 *
 * @version 1.0
 * @author cst231, cst238
 * @Route("/stats", name="stats")
 * @Security("is_granted('skip')")
 */
class CaregiverStatController extends Controller
{
    /**
     * Function that hard codes user login then calls a service to handle the rest of the work
     * of actually gathering the statistics and determining if the logged in user is authorized to
     * view the stats in the first place.
     * @param Request $request
     * @Route("/caregiver/{userID}")
     */
    public function getStats(Request $request, $userID)
    {

        $em = $this
            ->get('doctrine')
            ->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $stats = new GetStats($em);
            $responseArray = $stats->getCaregiverStats($user, $userID);
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


}