<?php
namespace AppBundle\Controller;

use AppBundle\Form\UserType;

use AppBundle\Entity\User;
use AppBundle\Entity\Relationship;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use AppBundle\Services\validateRelationship;
use DateTime;
use AppBundle\Entity\WellnessProfessional;
use AppBundle\Services\getCountFromDB;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * RegistrationController short summary.
 *
 * RegistrationController description.
 *
 * @version 1.0
 * @author cst233
 * @Route("/relationship")
 * @Security("is_granted('skip')")
 */
class RelationshipController extends Controller
{
    //array to return in JSON format, specifes the type of relationship created, if it succeeded or not, reason if it failed,
    //and two users involved in the relationship
    private $reply = array("message_type"=> "", "status"=>"", "reason"=>"", "relationship_initiator"=>null, "relationship_target" => null);

    /**
     *
     * adds another user as a friend.
     * validates that an existing user is passed in.
     * calls the addRelationship function to add the created relationship
     * to the database
     *
     * @param Request $request
     * @param User $user - passed in user to be added into a relationship
     *
     * @Route("/addFriend/{userID}")
     * @ParamConverter("user", class="AppBundle:User")
     *
     */
    public function addFriendAction(Request $request, User $user = null)
    {
        //if the user doesn't exist, will generate a failure status
        //and will assign a User not found message.
        //returns the error and will not call the addRelationship function
        if ($user === null)
        {
            $this->reply["message_type"] = "add_friend";
            $this->reply["status"] = "failure";
            $this->reply["reason"] = "User not found";
            $this->reply["relationship_initiator"] = $this->getUser();
            $this->reply["relationship_target"] = null;

            return new Response (json_encode($this->reply),200, array('Content-Type'=>'application/json'));
        }
        $responseArray = array();

        $userLoggedIn = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->addRelationship($userLoggedIn, $user,'friend' );

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
     * NOTE: this method needs to be removed once the log in story is finished!!!!!!!!!!
     * similar to the addFriend function - only used for testing
     * tests that a wellness professional can add another user as a friend
     *
     * @param Request $request
     * @param User $user - passed in user to be added into a relationship
     * @Route("/addFriendClient/{userID}")
     * @ParamConverter("user", class="AppBundle:User")
     *
     */
    public function addFriendClientAction(Request $request, User $user = null)
    {
        //if the user doesn't exist, will generate a failure status
        //and will assign a User not found message.
        //returns the error and will not call the addRelationship function
        if ($user === null)
        {
            $this->reply["message_type"] = "add_friend";
            $this->reply["status"] = "failure";
            $this->reply["reason"] = "User not found";
            $this->reply["relationship_initiator"] = $this->getUser();
            $this->reply["relationship_target"] = null;

            return new Response (json_encode($this->reply),200, array('Content-Type'=>'application/json'));
        }
        $responseArray = array();

        $userLoggedIn = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->addRelationship($userLoggedIn, $user,'support' );

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
     * adds another user as a supporter
     * validates that an existing user is passed in.
     * calls the addRelationship function to add the created relationship
     * to the database
     *
     * @param Request $request
     * @param User $user - passed in user to be added into a relationship
     * @Route("/addSupporter/{userID}")
     *@ParamConverter("user", class="AppBundle:User")
     *
     */
    public function addSupporterAction(Request $request, User $user = null)
    {

        if ($user === null)
        {
            $this->reply["message_type"] = "add_friend";
            $this->reply["status"] = "failure";
            $this->reply["reason"] = "User not found";
            $this->reply["relationship_initiator"] = $this->getUser();
            $this->reply["relationship_target"] = null;

            return new Response (json_encode($this->reply),200, array('Content-Type'=>'application/json'));
        }
        $responseArray = array();

        $setuser = $this->get('security.token_storage')->getToken()->getUser();
        //cst.project5.refresh+test1@gmail.com
        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->addRelationship($setuser, $user,'support' );

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
     *
     * adds another user as a supportee
     * validates that an existing user is passed in.
     * calls the addRelationship function to add the created relationship
     * to the database
     * @param Request $request
     * @param User $user - passed in user to be added into a relationship
     * @Route("/addSupportee/{userID}")
     *@ParamConverter("user", class="AppBundle:User")
     *
     */
    public function addSupporteeAction(Request $request, User $user = null)
    {
        //if the user doesn't exist, will generate a failure status
        //and will assign a User not found message.
        //returns the error and will not call the addRelationship function
        if ($user === null)
        {
            $this->reply["message_type"] = "add_supportee";
            $this->reply["status"] = "failure";
            $this->reply["reason"] = "User not found";
            $this->reply["relationship_initiator"] = $this->getUser();
            $this->reply["relationship_target"] = null;

            return new Response (json_encode($this->reply),200, array('Content-Type'=>'application/json'));
        }
        $responseArray = array();

        $userLoggedIn = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->addRelationship($userLoggedIn, $user,'supportee' );

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
     * adds a verified user as a wellness professional
     * validates that an existing user is passed in
     * calls the addRelationship function to add the created relationship
     *
     * @param Request $request
     * @param User $user - passed in user to be added into a relationship
     * @Route("/addWellnessProfessional/{userID}")
     * @ParamConverter("user", class="AppBundle:User")
     *
     */
    public function addWellnessProfessionalAction(Request $request, User $user = null)
    {
        //if the user doesn't exist, will generate a failure status
        //and will assign a User not found message.
        //returns the error and will not call the addRelationship function
        if ($user === null)
        {
            //Array for the JSON response when the user does not exist.
            $this->reply["message_type"] = "add_professional";
            $this->reply["status"] = "failure";
            $this->reply["reason"] = "User not found";
            $this->reply["relationship_initiator"] = $this->getUser();
            $this->reply["relationship_target"] = null;
            //returns the array as a JSON object
            return new Response (json_encode($this->reply),200, array('Content-Type'=>'application/json'));
        }


        $responseArray = array();

        $userLoggedIn = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->addRelationship($userLoggedIn, $user, 'wellness professional');

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
     * Wellness professional adds the client by
     * passing in the client/respondent's GUID to the url
     * @param Request $request
     * @param User $user - passed in user to be added into a relationship
     * @Route("/addClient/{userID}")
     * @ParamConverter("user", class="AppBundle:User")
     */
    function addClient(Request $request, User $user = null)
    {
        if ($user === null)
        {
            //Array for the JSON response when the user does not exist.
            $this->reply["message_type"] = "add_professional";
            $this->reply["status"] = "failure";
            $this->reply["reason"] = "User not found";
            $this->reply["relationship_initiator"] = $this->getUser();
            $this->reply["relationship_target"] = null;
            //returns the array as a JSON object
            return new Response (json_encode($this->reply),200, array('Content-Type'=>'application/json'));
        }


        $responseArray = array();

        $setuser = $this->get('security.token_storage')->getToken()->getUser();
        //cst.project5.refresh+loggedProf@gmail.com
        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->addRelationship($setuser, $user, 'client');

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
     * Validates that the user's ID is a special GUID string generated with
     * alphanumeric characters with a length of 24 characters.
     * @param mixed $guid
     * @return mixed
     */
    function isGuid($guid){
        return preg_match("/^(\{)?[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}(?(1)\})$/i", $guid)? $guid: false;
    }


    /**
     * NOTE: this method can be deleted once the log in story is finished,
     * used for testing purposes
     *
     * Test function that calls the addRelationship function with
     * relationship type of 'client' for a normal user
     * @param Request $request
     * @param User $user - passed in user to be added into a relationship
     * @Route("/addClientNotWP/{userID}")
     * @ParamConverter("user", class="AppBundle:User")
     */
    function addClientNotWP(Request $request, User $user = null)
    {
        //if the user doesn't exist, will generate a failure status
        //and will assign a User not found message.
        //returns the error and will not call the addRelationship function
        if ($user === null)
        {
            //Array for the JSON response when the user does not exist.
            $this->reply["message_type"] = "add_client";
            $this->reply["status"] = "failure";
            $this->reply["reason"] = "User not found";
            $this->reply["relationship_initiator"] = $this->getUser();
            $this->reply["relationship_target"] = null;
            //returns the array as a JSON object
            return new Response (json_encode($this->reply),200, array('Content-Type'=>'application/json'));
        }

        #region logging in
        //Logging in as default user
        //Need to remove when login is added
        //Entity Repository
        $em = $this
            ->get('doctrine')
            ->getManager();
        $repo  = $em->getRepository(User::class); //Entity Repository
        $setuser = $repo->findBy(['email' => "cst.project5.refresh+test1@gmail.com"]);

        if (!$setuser[0])
        {
            throw new UsernameNotFoundException("User not found");
        }
        else
        {
            $token = new UsernamePasswordToken($setuser[0], null, "our_db_provider", $setuser[0]->getRoles());
            $this->get("security.token_storage")->setToken($token); //now the user is logged in

            //now dispatch the login event

            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        }
        #endregion

        //returns a call to the addRelationship function to create a wellness professional/client relationship
        //with the passed in users. will not validate
        return $this->addRelationship($this->getUser(), $user, 'client');
    }

    /**
     * Creates the relationship between two passed in users
     * with the passed in type into the database.
     *
     * @param mixed $userOne - the calling user
     * @param mixed $userTwo - the target user
     * @param mixed $type - relationship type - friend, client, wellness professional, support
     * @return Response - JSON array, status of the relationship
     */
    function addRelationship($userOne, $userTwo, $type)
    {
        //uses target 1 for support user, target 2 for supportee
        $target = 1;

        if ( $type === 'supportee')
        {
            $target = 2;
            $type = "support";
        }

        $em = $this
            ->get('doctrine')
            ->getManager();


        //insert logic here
        /**Check if guid is valid
         * if valid open database
         *      switch (relationship)
         *              case 1: friend
         *              case 2: support
         *              case 3: wellness prof
         *              case 4: client
         *              default
         *      select userbyguid
         *      if select user exists
         *          create relationship
         *          set status to success
         *  return response
         */

        //assigns the passed in users to which user initiates the relationship and which is the target for the relationship
        $this->reply["relationship_initiator"] = $userOne->getUserId();
        $this->reply["relationship_target"] = $userTwo->getUserId();

        //validates the relationship that can be added
        //assigns appropriate status, reason to the reply if it fails
        if($target == 1)
        {
            $this->reply = validateRelationship::validate($userOne, $userTwo, $type, $target, $em, $this->reply);
        }
        else
        {
            $this->reply = validateRelationship::validate($userTwo,$userOne, $type, $target, $em, $this->reply);
        }

        //checks that the validation from the service has passed.
        if(!$this->reply['status'] == 'failure')
        //All validation has passed and the relationship will be created
        //If any issues occur an error will be returned
        //Attempts to create the relationship
        try
        {
            //Creating the relationship
            $relToAdd = new Relationship();

            //Assiging the two users to the relationship
            //Checks to see if the relationship was initiated by a supportee to assign the users
            if($target == 1)
            {
                $relToAdd->setUserIdOne($userOne);
                $relToAdd->setUserIdTwo($userTwo);
            }
            else
            {
                $relToAdd->setUserIdOne($userTwo);
                $relToAdd->setUserIdTwo($userOne);
            }

            //Setting the status to pending until the other user accepts (currently not implemented)
            $relToAdd->setStatus("pending");

            //Sets the type of the relationship to that of the passed in value
            $relToAdd->setType($type);

            //have to set after as the relationship id now contains the type
            //Appends the two user IDs with the type to uniquely identify the relationship
            $relToAdd->setRelationshipId();

            //Sets the date field to be todays date
            $relToAdd->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));

            //Persists the relationship in the database
            $em->persist($relToAdd);

            $em->flush();

            $this->reply['status'] = 'success';

        }
        //If any issues occur the error will be added to the reason and returned
        catch(\Exception $e)
        {
            $this->reply['status'] = 'failure';
            $this->reply['reason'] = $e->getMessage();
        }

        //JSON object returned to determine whether or not the relationship is added to the db
        return new Response (json_encode($this->reply),200, array('Content-Type'=>'application/json'));
    }
}