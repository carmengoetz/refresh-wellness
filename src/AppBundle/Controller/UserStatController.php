<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Services\GetStats;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use AppBundle\DataFixtures\CaregiverStatFixtures;
use AppBundle\Entity\Group;
use Symfony\Component\Config\Definition\Exception\Exception;

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
class UserStatController extends Controller
{
    /**
     * Function that hard codes user login then calls a service to handle the rest of the work.
     * This method will get the stats for the logged in user
     * @param Request $request
     * @Route("/me")
     */
    public function getMyStats(Request $request)
    {
        $responseArray = array();

        $em = $this
            ->get('doctrine')
            ->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $stats = new GetStats($em);

            $responseArray = $stats->getMyStats($user);
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
     * Function that calls a service to handle the rest of the work.
     * This method will get the stats for the logged in user's patient
     * @param Request $request
     * @Route("/patient/{userID}")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function getPatientStats(Request $request, User $user = null)
    {
        $responseArray = array();

        $em = $this
            ->get('doctrine')
            ->getManager();

        $wellnessPro = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $patient = $user;

            $stats = new GetStats($em);

            $responseArray = $stats->getPatientStats($wellnessPro, $patient);
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
     * Function that calls a service to handle the rest of the work.
     * This method will get the stats for the logged in user's org member
     * @param Request $request
     * @Route("/orgMember/{groupID}/{userID}")
     * @ParamConverter("group", class="AppBundle:Group")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function getOrgMemberStats(Request $request, Group $group = null, User $user = null)
    {
        $responseArray = array();

        $em = $this
            ->get('doctrine')
            ->getManager();

        $orgAdmin = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $orgMember = $user;

            $stats = new GetStats($em);

            $responseArray = $stats->getOrgMemberStats($orgAdmin, $orgMember, $group);
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
     * Simple function to render the front end page.
     * @Route("/view/{option}")
     */
    public function renderPage($option)
    {
        switch($option){
            case "patientAll":
                return $this->render("viewStats/view_aggregate_stats.html.twig", array("params" => $option));

            case "orgMemberAll":
                return $this->render("viewStats/view_aggregate_stats.html.twig", array("params" => $option));

            default:
                return $this->render("viewStats/view_individual_stats.html.twig", array("params" => $option));
        }
    }
    /**
     * Calls the getPatientStatsAll from the GetStats service to get an array containing an id and
     * all wellness stats for each patient of the wellness pro that is logged in. Returns the array
     * as a JSON object.
     * @param Request $request
     * @Route("/patientAll")
     */
    public function getPatientStatsAll(Request $request)
    {
        $responseArray = array();

        $em = $this
            ->get('doctrine')
            ->getManager();

        $wellnessPro = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $stats = new GetStats($em);

            $responseArray = $stats->getPatientStatsAll($wellnessPro);
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
     * Calls the getOrgMemberStatsAll from the GetStats service to get an array containing an id and
     * all wellness stats for each member of the org for the org admin that is logged in. Returns the array
     * as a JSON object.
     * @param Request $request
     * @Route("/orgMemberAll")
     */
    public function getOrgMemberStatsAll(Request $request)
    {
        $em = $this
            ->get('doctrine')
            ->getManager();

        //Get logged in user
        $orgAdmin = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $stats = new GetStats($em);

            $responseArray = $stats->getOrgMemberStatsAll($orgAdmin);
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