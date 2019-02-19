<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use AppBundle\Entity\Respondent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Httpful\Httpful;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use AppBundle\Services\HardLogIn;
use AppBundle\DataFixtures\RelationshipFixtures;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\GroupMember;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Generates the Group entity for the user to create
 * and stores the group name and group description to the
 * database.
 *
 * @version 1.0
 * @author cst245
 * @Route("/group", name="create_group")
 * @Security("is_granted('skip')")
 */
class GroupController extends Controller
{
    /**
     * Generates and returns JSON properties/objects to the page based on
     * what params are passed into the url for group objects
     * @param Request $request
     *
     * @Route("/startgroup/{json}", name="start_group")
     */
    public function startGroup(Request $request, $json)
    {
        $em = $this
           ->get('doctrine')
           ->getManager();

        $setRespondent = false;

        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {

            //change repo to look in respondent table
            $repo  = $em->getRepository(Respondent::class);

            //check to ensure that the logged in user has a role of respondent
            $setRespondent = $repo->findOneBy(['user' => $loggedInUser->userID]);

            //set up string to add to message that will be returned & name variable
            //to set name passed back & status property
            $data = array("groupName" => "", "groupDesc" => "", "groupType" => "", "groupAdmin" => "");

            $response = array("status" =>"", "message" => "", "data" => $data);

            //if ($loggedInUser && $setRespondent)
            //{
                //decode the json that has been passed in the URL params into a php object
                $groupObj = json_decode($json);

                //create a new group object
                $group = new Group();


                //set the group name and desc based on what was passed in
                $group->setGroupName($groupObj->groupName);
                $group->setGroupDesc($groupObj->groupDesc);

                $group->setGroupType($groupObj->groupType);

                //check to see group type


                switch ($group->getGroupType()){
                    case "organization":
                        //See if there is an admin set
                        $group->setGroupAdmin($loggedInUser);
                        $data['groupAdmin'] = $loggedInUser->getName();



                    case "standard":

                        $data['groupName'] = $groupObj->groupName;
                        $data['groupDesc'] = $groupObj->groupDesc;

                        //generate group id
                        $group->setgroupId(uniqid());

                        //Set group type
                        if ($group->getGroupType() === "standard")
                        {
                            $group->setGroupType("standard");
                            $data['groupType'] = "standard";
                        }
                        else if ($group->getGroupType() === "organization")
                        {
                            $group->setGroupType("organization");
                            $data['groupType'] = "organization";
                        }

                        //validate the group object
                        $validator = $this->get('validator');
                        $errors = $validator->validate($group);



                        /************if there are errors************/
                        if (count($errors) > 0)
                        {

                            //turn it into a string
                            $response['message'] = (string) $errors;

                            //if either attribute is empty , throw an error
                            //if the group name is empty
                            if (empty($group->getGroupName) && !(empty($group->getGroupDesc)))
                            {
                                $response['status'] = 'failure';
                                $response['message'] = "group not created, please enter a group name";
                            }

                            //if the group description is empty
                            else if (empty($group->getGroupDesc) && !(empty($group->getGroupName)))
                            {
                                $response['status'] = 'failure';
                                $response['message'] = " not created, please enter a group description";

                            }


                            //only spaces where an attribute should be
                            if ($group->getGroupName = '     ' && !(empty($group->getGroupDesc)))
                            {
                                $response['status'] = 'failure';
                                $response['message'] = "group not created, please enter a proper group name";
                            }
                            else if ($group->getGroupDesc = '      ' && !(empty($group->getGroupName)))
                            {
                                $response['status'] = 'failure';
                                $response['message'] = "group not created, please enter a proper group description";
                            }



                        }
                        /************if there aren't any errors************/
                        else
                        {
                            $em = $this->getDoctrine()->getManager();

                            //set success of adding to db to true by default;
                            $success = true;
                            $response['status'] = "success";

                            $response['data'] = $data;

                            $data['groupName'] = $groupObj->groupName;
                            $data['groupDesc'] = $groupObj->groupDesc;



                            try
                            {
                                //tells Doctrine you want to eventually save the Group object (but no queries yet)
                                $em->persist($group);
                                //actually executes the queries
                                $em->flush();

                            }
                            catch(Exception $e)
                            {
                                //set success to false if there is an issue adding to the db
                                $success = false;
                                $response['status'] = 'failure';
                                $response['message'] = $data['groupName'] . " was not created in the database";
                            }

                            //if the record was added successfully
                            if($success)
                            {

                                //set the status to success
                                $response['status'] = "success";
                                $response['message'] =  $data['groupName'] . " created in the database";

                            }


                        }
                        break;
                    default:
                        $groupType = $group->getGroupType();
                        //see if they entered a group
                        if(!empty($groupType)){
                            $response['status'] = "failure";
                            $response['message'] ="group type must be standard or organization.";
                        }else{ //No group was entered
                            $response['status'] = "failure";
                            $response['message'] ="group must have a group type. (either Standard or Organization).";
                        }
                        break;
                }
            //}
            //if the user is not logged in or isn't a respondent
            //else
            //{
            //    $response['status']= "na";
            //    $response['message'] = 'user not logged in';
            //}
        }
        else
        {
            $response = array(
                'status'=>"failure",
                'message'=>"User is not logged in.",
                'data'=> array( ));
        }

        return new JsonResponse($response);

    }

    /**
     * This method will add the logged in user to the passed in group object
     * @param Request $request
     * @param Group $group - passed in group id for logged in User to join
     * @Route("/join/{group}")
     * @ParamConverter("group", class="AppBundle:Group")
     */
    public function joinGroup(Request $request, Group $group = null)
    {
        //array to display the information concerning the group that the user was added to
        $data = array("groupID" => "", "groupName" => "", "memberID" => "");

        //final array to get back from the page
        $response = array("status" =>"", "message" => "", "data" => $data);

        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();

        //Makes sure a user is logged in and not tha anonymous user.
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            //jjjjjjjjjjjjjjjjjjjjjjjjjjhsdffgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgfgd
            $em = $this->get('doctrine')->getManager();

            //if group doesn't exist, it will generate a failure status
            if ($group === null)
            {
                //Array for the JSON response when the group does not exist
                $response["status"] = "failure";
                $response["message"] = "Group not found.";
                $response["data"] = array();
            }
            else //the group Does exist
            {

                $em->refresh($loggedInUser);

                $found = false;
                //Loop through all the groups they've joined and look for the one matching the group
                foreach ($loggedInUser->groupsJoined as $group1)
                {
                    if ( $group1->getGroup()->getgroupId() === $group->getgroupId() )
                    {
                        $found = true;
                    }
                }

                if (!$found)
                {



                    //creates a group member object
                    $newMember = new GroupMember();
                    $newMember->setUser($loggedInUser);
                    $newMember->setGroup($group);

                    $newMember->setDateJoined(new \DateTime("now"));

                    $newMember->setGroupRole("member");

                    $newMember->setStatus("active");



                    $newMember->setGroupMemberId();


                    $em->persist($newMember);
                    $em->flush();

                    $response["status"] = "success";
                    $data["groupID"] = $group->getgroupId();
                    $data["groupName"] = $group->getGroupName();
                    $data["memberID"] = $newMember->getGroupMemberId();

                    $response["message"] = "";

                    $response["data"] = $data;
                }
                else
                {
                    $response["status"] = "failure";
                    $response["message"] = "You are already a member of the group " . $group->getGroupName() . ".";
                    $response["data"] = array();
                }
            }

            //dsfffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff
        }
        //what is returned when the user is not logged in
        else
        {
            $response["status"] = "failure";
            $response["message"] = "You must be logged in to join a group.";
            $response["data"] = array();
        }


        return new JsonResponse($response);

    }
}