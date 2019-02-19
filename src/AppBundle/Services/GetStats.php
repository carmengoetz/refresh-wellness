<?php
namespace AppBundle\Services;
/**
 * GetStats short summary.
 *
 * GetStats description.
 *
 * @version 1.0
 * @author cst231
 */

use AppBundle\Entity\User;
use AppBundle\Entity\Relationship;
use AppBundle\Entity\Respondent;
use AppBundle\Entity\Wellness;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Group;
use AppBundle\Entity\GroupMember;

/**
 * Service to determine if the caregiver has the authority to view the user's stats, if the user exists,
 * actually returning the stats, etc.
 */
class GetStats
{

    private $em;

    /**
     * Sets the entity manager in the class
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Gets the stats for a user passed in and returns them
     * Used in the myStats controller to return the logged
     * in users stats.
     * @param mixed $user -User to get stats for
     */
    public function getMyStats($user)
    {
        //Instantiate status, data, id, and message
        $status = "failure";
        $data = array();
        $message = "";

        //check to see if user exists in the database
        $repo = $this->em->getRepository(User::class);
        $userToFind = $repo->findOneBy(['userID' => $user->userID]);
        $nameOfUser = '';
        //Make sure the user exists in the database

        if (!isset($userToFind))
        {
        	$message = 'User is not logged in';
        }
        else
        {
            $repo = $this->em->getRepository(Respondent::class);
            $respToFind = $repo->findOneBy(['user' => $userToFind->userID]);

            //make sure the user is a respondent
        	if (!isset($respToFind))
            {
                $message = 'You are not a Respondent';
            }

            else
            {
                //Find all records for the respondent
                $repo  = $this->em->getRepository(Wellness::class);
                $stats = $repo->findBy(['respondent'=>$respToFind->getRespondentID()]);

                //add each record to the data array for outputting
                foreach($stats as $stat){
                    $data[] = $stat->getStats();
                }

                $status = "success";
                $nameOfUser = $userToFind->getName();
            }

        }

        //Set the return array
        $stats = array(
            'status'=>$status,
            'message'=>$message,
            'data'=> array(
                'name'=>$nameOfUser,
                'stats'=> $data
                )
            );

        return $stats;

    }


    /**
     * Gets caregiver stats
     * @param mixed $caregiverUser -Caregiver User Object
     * @param mixed $userID -Id for the user you want to attempt to get stats for
     * @return array
     */
    public function getCaregiverStats($caregiverUser, $userID)
    {
        //Instantiate status, data, id, and message
        $status = "failure";
        $data = array();
        $id = $userID;
        $message = "";

        //See if the caregiver exists

        //Login a user as we do not have a login story yet
        $repo  = $this->em->getRepository(User::class); //Entity Repository
        $caregiver = $repo->findOneBy(['userID' => $caregiverUser->userID]);
        $caregivee = $repo->findOneBy(['userID' => $userID]);
        $nameOfUser = '';

        //Check to see if caregiver exists
        if(isset($caregiver))
        {
            //Check to see if caregivee exists
            if(!isset($caregivee))
            {
                $message .= "The user specified does not exist.";
            }
            else
            {
                //Check to see if there is a relationship between them
                $repo  = $this->em->getRepository(Relationship::class);
                $relationship = $repo->findOneBy(['relationshipId'=>$caregiver->userID . ":" . $caregivee->userID . ":" . "support"]);

                if(!isset($relationship))
                {
                    $message .= "You do not have the correct permissions to view this users stats.";
                }
                else
                {
                    //Relationship exists, get respondent id of given user
                    $repo  = $this->em->getRepository(Respondent::class);
                    $respondent = $repo->findOneBy(['user'=>$caregivee->userID]);

                    //make sure that the user has a respondent associated
                    //If the user is not a respondent
                    if(!isset($respondent)){
                        $message .= "User is not a respondent.";

                    //If the user is a respondent
                    }else{
                        //Find all records for the respondent
                        $repo  = $this->em->getRepository(Wellness::class);
                        $stats = $repo->findBy(['respondent'=>$respondent->getRespondentID()]);

                        //add each record to the data array for outputting
                        foreach($stats as $stat){
                            $data[] = $stat->getStats();
                        }

                        $status = "success";
                        $nameOfUser = $caregivee->getName();
                    }
                }
            }
        }
        else
        {
            //If caregiver doesn't exist
            $message .= "Please login to view stats.";
        }

        //Set the return array
        $stats = array(
            'status'=>$status,
            'message'=>$message,
            'data'=> array(
                'id'=>$id,
                'name'=>$nameOfUser,
                'stats'=> $data
                )
            );

        return $stats;
    }

    /**
     * Gets patient stats
     * @param mixed $wellnessPro -Wellness Professional User Object
     * @param mixed $patient - user you want to attempt to get stats for
     * @return array
     */
    public function getPatientStats(User $wellnessPro = null, User $patient = null)
    {
        //Instantiate status, data, id, and message
        $status = "failure";
        $data = array();
        $id = !empty($patient) ? $patient->getUserId() : "";
        $message = "";
        $nameOfUser = '';
        //Check to see if wellness pro exists
        if(isset($wellnessPro))
        {
            //Check to see if patient exists
            if(!isset($patient))
            {
                $message .= "The user specified does not exist.";
            }
            else
            {
                //Check to see if there is a relationship between them
                $repo  = $this->em->getRepository(Relationship::class);
                $relationship = $repo->findOneBy(['relationshipId'=>$patient->getUserId() . ":" . $wellnessPro->getUserId() . ":" . "wellness professional"]);

                if(!isset($relationship))
                {
                    $relationship = $repo->findOneBy(['relationshipId'=>$wellnessPro->getUserId() . ":" .$patient->getUserId() . ":" . "client"]);
                    if(!isset($relationship))
                    {
                        $message .= "You do not have the correct permissions to view this users stats.";
                    }
                }

                if (isset($relationship))
                {
                    //Relationship exists, get respondent id of given user
                    $repo  = $this->em->getRepository(Respondent::class);
                    $respondent = $repo->findOneBy(['user'=>$patient->getUserId()]);


                    //make sure that the user has a respondent associated
                    //If the user is not a respondent
                    if(!isset($respondent)){
                        $message .= "User is not a respondent.";

                        //If the user is a respondent
                    }else{
                        //Find all records for the respondent
                        $repo  = $this->em->getRepository(Wellness::class);
                        $stats = $repo->findBy(['respondent'=>$respondent->getRespondentID()]);

                        //add each record to the data array for outputting
                        foreach($stats as $stat){
                            $data[] = $stat->getStats();
                        }

                        $nameOfUser = $this->em->getRepository(User::class)
                            ->findOneBy(["userID"=>$id])->getName();

                        $status = "success";
                    }
                }
            }
        }
        else
        {
            //If caregiver doesn't exist
            $message .= "Please login to view stats.";
        }
        $stats = array(
           'status'=>$status,
           'message'=>$message,
           'data'=> array(
               'id'=>$id,
               'name'=>$nameOfUser,
               'stats'=>$data
               )
           );

        return $stats;
    }


    /**
     * Gets Org member stats
     * @param mixed $orgAdmin -Org Admin User Object
     * @param mixed $orgMember - user you want to attempt to get stats for
     * @return array
     */
    public function getOrgMemberStats(User $orgAdmin = null, User $orgMember = null, Group $org = null)
    {

        //Instantiate status, data, id, and message
        $status = "failure";
        $data = array();
        $id = !empty($orgMember) ? $orgMember->getUserId() : "";
        $message = "";
        $nameOfUser = '';

        //Check to see if orgadmin exists
        if(isset($orgAdmin))
        {
            //Check to see if orgmember exists
            if(!isset($orgMember))
            {
                $message .= "The user specified does not exist.";
            }
            else
            {
                //check to see if group exists
                if(!isset($org))
                {
                    $message .= "The group specified does not exist.";
                }
                else
                {
                    //Check if the group is an org
                    if ($org->getGroupType() === "organization")
                    {
                        //Check to see if the org member is a member of any org group which the admin is an admin of
                        $orgAdminIsMemberOfOrg = false;
                        $groupMemberObject = null;

                        //loop through all group memberships for the admin user and see if any are the group we are interested in
                        foreach($orgAdmin->groupsJoined as $grpAdm)
                        {
                            if ( $grpAdm->getGroup()->getgroupId() === $org->getgroupId())
                            {
                                $orgAdminIsMemberOfOrg = true;
                                $groupMemberObject = $grpAdm;
                            }
                        }

                        //IF the org admin is a member of the group we want, proceed
                        if ($orgAdminIsMemberOfOrg)
                        {
                            //Check if org admin is admin of the group in question
                            if( $groupMemberObject->getGroupRole() === 'admin')
                            {
                                //Loop through group memberships of the member user and see if any are in the group we want
                                $orgMemIsMemberOfOrg = false;

                                foreach($orgMember->groupsJoined as $grpMem)
                                {
                                    if ( $grpMem->getGroup()->getgroupId() === $org->getgroupId())
                                    {
                                        $orgMemIsMemberOfOrg = true;

                                    }
                                }

                                //If member is member of the org, proceed
                                if ($orgMemIsMemberOfOrg)
                                {
                                    //Get the respondent-ness of the member user
                                    $repo  = $this->em->getRepository(Respondent::class);
                                    $respondent = $repo->findOneBy(['user'=>$orgMember->getUserId()]);


                                    //make sure that the user has a respondent associated
                                    //If the user is not a respondent
                                    if(!isset($respondent))
                                    {
                                        $message .= "User is not a respondent.";

                                        //If the user is a respondent
                                    }
                                    else
                                    {
                                        //Find all records for the respondent
                                        $repo  = $this->em->getRepository(Wellness::class);
                                        $stats = $repo->findBy(['respondent'=>$respondent->getRespondentID()]);

                                        //add each record to the data array for outputting
                                        foreach($stats as $stat)
                                        {
                                            $data[] = $stat->getStats();
                                        }
                                        $status = "success";
                                        $nameOfUser = $this->em->getRepository(User::class)
                                            ->findOneBy(["userID"=>$id])->getName();
                                    }
                                }
                                //Member is not member of the org in question
                                else
                                {
                                    $message .= "The specified user is not a member of this organization.";
                                }

                            }
                            //Org admin user is NOT admin of the group in question
                            else
                            {
                                $message .= "You do not have the correct permissions to view this users stats.";
                            }

                        }
                        //Org admin not member of group in question
                        else
                        {
                            $message .= "You are not a member of this group.";
                        }
                    }
                    //The group is not an org
                    else
                    {
                        $message .= "This group is not an organization.";
                    }
                }
            }
        }
        else
        {
            //If org admin doesn't exist
            $message .= "Please login to view stats.";
        }

        //set up return array with information in it
        $stats = array(
           'status'=>$status,
           'message'=>$message,
           'data'=> array(
               'id'=>$id,
               'name'=>$nameOfUser,
               'stats'=>$data
               )
           );

        return $stats;
    }

    /**
     * Gets all patients' stats of the wellness pro that is logged in (passed in)
     * @param User $wellnessPro - Wellness Professional User object
     * @return array
     */
    public function getPatientStatsAll(User $wellnessPro = null)
    {
        //Instantiate status, message, and patients array
        $status = "failure";
        $message = "";
        $patients = array();
        $wp = $wellnessPro->getName();

        //Check to see if wellness pro exists
        if(isset($wellnessPro))
        {
            //Find all patients of the logged in wellness pro
            $repo = $this->em->getRepository(Relationship::class);
            $wpPatients = $repo->findBy(array('type'=>'wellness professional', 'userIdTwo'=>$wellnessPro->getUserId()));
            $wpClients = $repo->findBy(array('type'=>'client', 'userIdOne'=>$wellnessPro->getUserId()));
            //Check to see if the wellness pro has any patients
            if(count($wpPatients) > 0)
            {
                //If so, find the respondent records corresponding to each one,
                //then loop through them all and add their stats and id to the patients array
                foreach ($wpPatients as $patient)
                {
                    //Find corresponding respondent
                    $repo = $this->em->getRepository(Respondent::class);
                	$respondent = $repo->findOneBy(array('user'=>$patient->getUserIdOne()));
                    if ($respondent)
                    {
                        //Find each wellness record for that respondent
                        $repo = $this->em->getRepository(Wellness::class);
                        $wellnessRecords = $repo->findBy(array('respondent'=>$respondent->getRespondentID()));

                        //Loop through each wellness record and add it to a stats array
                        $stats = array();
                        foreach ($wellnessRecords as $record)
                        {
                            $stats[] = $record->getStats();
                        }

                        //Add patient id and stats array to patients array
                        $patients[] = array('userID'=>$respondent->getUser()->getUserID(), 'stats'=>$stats);
                    }
                }
            }

            if(count($wpClients) > 0)
            {
                //If so, find the respondent records corresponding to each one,
                //then loop through them all and add their stats and id to the patients array
                foreach ($wpClients as $client)
                {
                    //Find corresponding respondent
                    $repo = $this->em->getRepository(Respondent::class);
                	$respondent = $repo->findOneBy(array('user'=>$client->getUserIdTwo()));

                    if ($respondent)
                    {
                        //Find each wellness record for that respondent
                        $repo = $this->em->getRepository(Wellness::class);
                        $wellnessRecords = $repo->findBy(array('respondent'=>$respondent->getRespondentID()));

                        //Loop through each wellness record and add it to a stats array
                        $stats = array();
                        foreach ($wellnessRecords as $record)
                        {
                            $stats[] = $record->getStats();
                        }

                        //Add patient id and stats array to patients array
                        $patients[] = array('userID'=>$respondent->getUser()->getUserID(), 'stats'=>$stats);
                    }
                }
            }

            //Set status to 'success'
            $status = "success";
        }
        else //Wellness pro is not valid/logged in
        {
            $message .= "Please login to view stats.";
        }

        //Set and return final array
        $stats = array(
            'status'=> $status,
            'message'=> $message,
            'data'=> array(
                    'WellnessPro'=>$wp,
                    'patients'=> $patients
                ),
            );

        return $stats;
    }

    /**
     * Gets all patients' stats of the org for the org admin that is logged in (passed in)
     * @param User $orgAdmin - org admin User object
     * @return array
     */
    public function getOrgMemberStatsAll(User $orgAdmin = null)
    {
        //Instantiate status, message and members array
        $status = "failure";
        $message = "";
        $members = array();
        $orgName = '';

        //Check to see if the org admin exists
        if (isset($orgAdmin))
        {
            //Find all members of the group
            $repo = $this->em->getRepository(GroupMember::class);

            //check if the org Admin user has the role of 'admin'
            $org = $repo->findOneBy(array('user' => $orgAdmin->getUserId(), 'groupRole' => 'admin'));
            if(!$org == null)
            {
                $grMembers = $repo->findBy(array('group' => $org->getGroup()));

                $orgName = $this->em->getRepository(Group::class)->findOneBy(array('groupID'=> $org->getGroup()))->getGroupName();


                //Check to see if the organization has any members
                if (count($grMembers) > 0)
                {
                    //find the respondent rec ords corresponding to each one,
                    //then loop through them all and add their stats and id to the members array
                    foreach($grMembers as $member)
                    {
                        //find the corresponding respondent
                        $repo = $this->em->getRepository(Respondent::class);
                        $respondent = $repo->findOneBy(array('user' => $member->getUser()));

                        //Make sure member is a respondent (aka not an admin)
                        if(!empty($respondent))
                        {
                            //Find each wellness record for that respondent
                            $repo = $this->em->getRepository(Wellness::class);
                            $wellnessRecords = $repo->findBy(array('respondent' => $respondent->getRespondentID()));

                            //loop through each wellness record and add it to the array
                            $stats = array();
                            foreach($wellnessRecords as $record)
                            {
                                $stats[] = $record->getStats();
                            }

                            //add member id and stats array to members array
                            $members[] = array('userID' => $respondent->getUser()->getUserID(), 'stats' => $stats);
                        }
                    }
                }

                //set status to success
                $status = "success";
            }
            else
            {
                $message .= "You do not have the correct permissions to view this organization's stats.";
            }
        }

        else //org admin is not valid/logged in
        {
            $message .= "Please log in to view stats";
        }


        //Set and return final array
        $stats = array(
            'status'=> $status,
            'message'=> $message,
            'data'=> array(
                    'OrgName'=>$orgName,
                    'members'=> $members
                ),
            );

        return $stats;
    }

}