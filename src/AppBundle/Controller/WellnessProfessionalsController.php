<?php
namespace AppBundle\Controller;
use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use AppBundle\Entity\Relationship;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use DateTime;
use AppBundle\Services\HardLogIn;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Services\ViewWellnessProfessionals;
use AppBundle\DataFixtures\RelationshipFixtures;
/**
 * ViewWellnessProfessionalsController short summary.
 *
 * ViewWellnessProfessionalsController description.
 *
 * @version 1.0
 * @author cst213
 * @Route("/WellnessProfessionals")
 * @Security("is_granted('skip')")
 */
class WellnessProfessionalsController extends Controller
{
    /**
     * Simple function to render the front end page.
     * @Route("/nearby", name="nearbyWP")
     */
    public function renderPage()
    {
        return $this->render("nearbyProfessionals/view_nearby_wellness_professionals.html.twig");
    }


    /**
     * Summary of viewWellnessProfessionals
     * @param Request $request
     *
     * @Route("/view/{pageNum}")
     */
    public function viewWellnessProfessionals(Request $request, $pageNum = 1)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            //Get the response
            $response = ViewWellnessProfessionals::generateArray($user->getCity(),$pageNum, $this->get('doctrine')->getManager());
        }
        else
        //Otherwise return error
        {
            $response = array("status" => "failure", "message" => "User not authenticated", "data" => "");
        }

        return new JsonResponse($response);
    }

}
