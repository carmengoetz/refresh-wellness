<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Services\GetStats;
use AppBundle\Services\ConvertToArray;
use AppBundle\Entity\User;
use AppBundle\Entity\Relationship;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use AppBundle\DataFixtures\CaregiverStatFixtures;
use AppBundle\Entity\Group;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Controller for loading profiles (both front end and API)
 *
 * @version 1.0
 * @author cst231, cst238
 * @Route("/profile", name="profile")
 * @Security("is_granted('skip')")
 */
class ProfileController extends Controller
{
    /**
     * Function that hard codes user login then calls a service to handle the rest of the work.
     * This method will get the stats for the logged in user
     * @param Request $request
     * @Route("/view/{userID}")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function getProfile(Request $request, User $user)
    {
        $responseArray = array();

        $userlogin = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {


            //Get relationships from the user
            $relationshipsInit = $user->relationshipsInitiated->getValues();
            $relationshipsReq = $user->relationshipsRequested->getValues();

            $relationshipsarray = array_merge($relationshipsInit, $relationshipsReq);

            //checking if logged in user is a wellness professional
            $selfWellPro = 0;

            if($userlogin->getWellnessProfessional() != null)
            {
                $selfWellPro = 1;
            }

            //User exists
            if ($user != null)
            {
                $self = $user == $userlogin ? 1 : 0;

                $pracName = 0;

                if($user->getWellnessProfessional() != null)
                {



                    $pracName =array("pracName" => $user->getWellnessProfessional()->getPracticeName(),
                            "contactEmail" => $user->getWellnessProfessional()->getContactEmail(),
                            "contactNumber" => $user->getWellnessProfessional()->getContactNumber(),
                            "website" => $user->getWellnessProfessional()->getWebsite()
                        )
                        ;
                }


                //Array for user data
                $userObject = array(
                        "name" => $user->getName(),
                        "isWellnessPro" => $pracName,
                        "city" => $user->getCity(),
                        "numFriends" => $user->getNumFriends(),
                        "numSupporters" => $user->getNumSupporters(),
                        "numSupportees" => $user->getNumSupportees(),
                    );

                //Relationship indicators

                $relationships = array("isFriend" => 0,
                    "isSupporter" => 0,
                    "isSupportee" => 0,
                    "isWellnessProRel" => 0);
                //If requested user does not match logged in user
                if (!$self)
                {
                    $converter = new ConvertToArray();
                    $relationships = $converter->convertToArrayProfileRelationships($relationshipsarray, $userlogin);
                }

                //Create return array for success
                $responseArray = array(
                    'status'=>"success",
                    'message'=>"",
                    'data'=> array(
                        "self" => $self,
                        'selfWellPro' => $selfWellPro,
                        "user" => $userObject,
                        "relationships" => $relationships

                        )
           );

            }
            else
            {
                //User does not exist
                $responseArray = array(
                'status'=>"failure",
                'message'=>"User does not exist.",
                'data'=> array()
                );
            }


        }else{
            //User not logged in
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
     * @Route("/")
     * @Route("/{user}")
     */
    public function renderProfile($user = null)
    {
        $userLogin = $this->get('security.token_storage')->getToken()->getUser();

        $error = '';
        $selfGUID = '';
        $statsLink = '';

        //Get logged in user
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $error = 'You are not logged in.';
        }

        else
        {
            //Check if logged in user is the user who's profile we are accessing
            $selfGUID = $userLogin->getUserId();

            if ($user == null)
            {
                $user = $selfGUID;
            }

            $statsLink = "supportee";

            //Check if we are a WP
            if ($userLogin->getWellnessProfessional())
            {
                $statsLink = "patient";
            }
        }

        $options = array('self' => $selfGUID, 'other' => $user, "statsLink" => $statsLink, 'error' => $error);

        //render page with option
        return $this->render("profile/profile.html.twig", array('params'=> $options));



    }


}