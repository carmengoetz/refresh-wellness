<?php
namespace AppBundle\Services;

/**
 * Service to perform validation before creation of
 * a relationship in the database
 *
 * validateRelationship description.
 *
 * @version 1.0
 * @author CST236
 */

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Relationship;
use AppBundle\Services\getCountFromDB;
use AppBundle\Entity\WellnessProfessional;

class validateRelationship
{
    private static $MAX_FRIENDS = 100;
    /**
     * Generic validation method for adding a relationship.
     * Calls the other validation methods depending on the relationship type.
     * @param mixed $userOne - The initiating user
     * @param mixed $userTwo - The user to add
     * @param mixed $type - The type of relation to validate
     * @param mixed $target - Whether the validation is for a support/ supportee
     * @param mixed $em - The entity manager used to check the database
     * @param mixed $reply - The associative array to add failure case and reason to
     * @return string[] - An associative array that determines if validation has passed
     */
    public static function validate($userOne, $userTwo, $type, $target, $em, $reply)
    {
        //Checks if the initiator and target are the same
        //will set status to failure if the user tried to add themselves
        if ($reply['relationship_initiator'] === $reply['relationship_target'])
        {
            $reply['status'] = 'failure';
            $reply['reason'] = 'You cannot add yourself as a ' . $type . '.';
        }
        else
        {
            //Switch case to call relationship specific validation.
            switch ($type)
            {
                //Validation for a friend relationship
                case 'friend':
                    $reply = validateRelationship::validateAddFriend($userOne, $userTwo, $reply);
                    break;
                //Validation for a support relationship
                case 'support':
                    $reply = validateRelationship::validateAddSupport($userOne, $userTwo, $target, $reply);
                    break;
                //Validation for a professional relationship
                case 'wellness professional':
                    $reply = validateRelationship::validateAddWellnessProfessional($userOne, $userTwo, $em, $reply);
                    break;
                //Validation for a client relationship
                case 'client':
                    $reply = validateRelationship::validateAddClient($userOne, $userTwo, $em, $reply);
                    break;
                //failure case if an invalid type is passed in for validation.
                default:
                    $reply['status'] = 'failure';
                    $reply['reason'] = 'Invalid relationship type';
                    break;
            }
            //If any of the previous validation failed no other validation will occur.
            if(!$reply['status'] == 'failure')
            {
                //checking the database to see if the relationship already exists with the passed in type
                $existingRelationship = $em
                ->getRepository(Relationship::class)
                ->findOneBy(['userIdOne' => $userOne, 'userIdTwo' => $userTwo, 'type' => $type]);
                

                //Checks if the relationship was set, will set the failure case if so.
                if (isset($existingRelationship))
                {
                    $reply['status'] = 'failure';
                    $reply['reason'] = 'relationship already exists';
                }
                else
                {
                    //Checks if the user that was being added has a disabled account, will set the failure case if so.
                    if ($userTwo->getStatus() === 'inactive' || $userOne->getStatus() === 'inactive' )
                    {
                        $reply['status'] = 'failure';
                        $reply['reason'] = 'target user is disabled';
                    }
                }
            }
        }

        //If all validation has passed reply should only have the message_type parameter set to
        //indicate the type of relationship that was added. Otherwise will contain a failure status
        //and the reason for failure.
        return $reply;
    }

    /**
     * Function to validate before adding a friend
     * @param mixed $userOne - The initiating user
     * @param mixed $userTwo - The user to add
     * @param mixed $reply - The associative array to add failure case and reason to
     * @return string[] - An associative array that determines if validation has passed
     */
    public static function validateAddFriend($userOne, $userTwo, $reply)
    {
        //Set the message_type to add_friend to indicate that a friend relationship was attempted
        $reply['message_type'] = 'add_friend';



        //Get relationships from the user
        $relationshipsInit = $userOne->relationshipsInitiated->getValues();
        $relationshipsReq = $userOne->relationshipsRequested->getValues();

        $relationshipsarrayUserOne = array_merge($relationshipsInit, $relationshipsReq);


        //Boolean to see if a relationship was found
        $matchingRelationshipFound = false;

        //Loop through all user relationships and look for any with both these users of type friend
        foreach ($relationshipsarrayUserOne as $rel)
        {
            if (($rel->getUserIdOne() == $userOne || $rel->getUserIdTwo() == $userOne) &&
                $rel->getUserIdOne() == $userTwo || $rel->getUserIdTwo() == $userTwo)
            {
                //Check type of relationship and set up variables for return
                if ($rel->getType() == 'friend')
                {
                    $matchingRelationshipFound = true;
                    $reply['status'] = 'failure';
                    $reply['reason'] = 'Already friends';

                }
            }
        }

        if (!$matchingRelationshipFound)
        {
            //checks how many friends userOne has, will fail if they are over the limit
            if ($userOne->getNumFriends() >= 100)
            {
                $reply['status'] = 'failure';
                $reply['reason'] = 'max friends reached';
            }
            //checks how many friends useTwo has, will fail if they are over the limit
            else if ($userTwo->getNumFriends() >= 100)
            {
                $reply['status'] = 'failure';
                $reply['reason'] = 'target user max friends reached';
            }
            //Increments each users associated friend count
            $userOne->incrementFriends();
            $userTwo->incrementFriends();
        }

        return $reply;

    }

    /**
     * Function to validate before adding a Support/Supportee
     * @param mixed $userOne - The initiating user
     * @param mixed $userTwo - The user to add
     * @param mixed $target - Whether the validation is for a support/ supportee
     * @param mixed $reply - The associative array to add failure case and reason to
     * @return string[] - An associative array that determines if validation has passed
     */
    public static function validateAddSupport($userOne, $userTwo, $target, $reply)
    {
        //Set the message_type to add_support to indicate that a support relationship was attempted
        $reply['message_type'] = 'add_support';

        //Checks if the relationship is being called by a support user and validates that they are not over their limits
        if ( $target === 1 && $userOne->getNumSupporters() > 5 && $userTwo->getNumSupportees() > 5)
        {
            $reply['status'] = 'failure';
            $reply['reason'] = 'Max supportees reached.';
        }
        //Checks if the relationship is being called by a supportee user and validates that they are not over their limits
        elseif($target !== 1 && $userOne->getNumSupportees() > 5 && $userTwo->getNumSupporters() > 5)
        {
            $reply['status'] = 'failure';
            $reply['reason'] = 'Max supporters reached.';
        }

        //increments each users number of supportors/ supportees for the appropriate values
        
            $userOne->incrementSupporters();
            $userTwo->incrementSupportees();
        

        return $reply;

    }

    /**
     * Function to validate before adding a Wellness Professional
     * @param mixed $userOne - The initiating user
     * @param mixed $wpTwo - The Wellness Professional to add
     * @param mixed $em - The entity manager used to check the database
     * @param mixed $reply - The associative array to add failure case and reason to
     * @return string[] - An associative array that determines if validation has passed
     */
    public static function validateAddWellnessProfessional($userOne, $wpTwo, $em, $reply)
    {
        //Set the message_type to add_professional to indicate that a professional relationship was attempted
        $reply['message_type'] = 'add_professional';

        //set a variable to see if the user is a professional
        $isProf = $em->getRepository(WellnessProfessional::class)->findOneBy(['user' => $wpTwo->getUserId()]);

        //Get relationships from the user
        $relationshipsInit = $userOne->relationshipsInitiated->getValues();
        $relationshipsReq = $userOne->relationshipsRequested->getValues();

        $relationshipsarrayUserOne = array_merge($relationshipsInit, $relationshipsReq);


        //Boolean to see if a relationship was found
        $matchingRelationshipFound = false;

        //Loop through all user relationships and look for any with both these users of type friend
        foreach ($relationshipsarrayUserOne as $rel)
        {
            if (($rel->getUserIdOne() == $userOne || $rel->getUserIdTwo() == $userOne) &&
                $rel->getUserIdOne() == $wpTwo || $rel->getUserIdTwo() == $wpTwo)
            {
                //Check type of relationship and set up variables for return
                if ($rel->getType() == 'client' || $rel->getType() == 'wellness professional')
                {
                    $matchingRelationshipFound = true;
                    $reply['status'] = 'failure';
                    $reply['reason'] = 'Patient relationship already exists';

                }
            }
        }

        if (!$matchingRelationshipFound)
        {

            //Check to ensure the user is adding a valid Wellness Professional
            //Will set status to failure if they are any other type of user
            if(!isset($isProf))
            {
                $reply['status'] = 'failure';
                $reply['reason'] = 'User is not a qualified wellness professional.';
            }
            //Returns a count of the users wellness professionals list
            //Will set status to failure if the max is reached
            elseif(getCountFromDB::countRelationship($userOne->getUserId(), 'wellness professional', $em) >= 10)
            {
                $reply['status'] = 'failure';
                $reply['reason'] = 'Max wellness professionals reached.';
            }
            //Check to see if the Wellness Professional has filled their patient list
            //Will set status to failure if they have reached the limit
            elseif(getCountFromDB::countPatients($wpTwo->getUserId(), 'wellness professional', $em) >= 10)
            {
                $reply['status'] = 'failure';
                $reply['reason'] = 'Wellness professionals patient list is full';
            }
        }
        return $reply;

    }

    /**
     * Function to validate before adding a client
     * @param mixed $wpOne - The initiating WellnessProfessional
     * @param mixed $userTwo - The user to add
     * @param mixed $em - The entity manager used to check the database
     * @return string[] - An associative array that determines if validation has passed
     */
    public static function validateAddClient($wpOne, $userTwo, $em, $reply)
    {
        //Set the message_type to add_client to indicate that a client relationship was attempted
        $reply['message_type'] = 'add_client';

        //set a variable to see if the user is a professional
        $isProf = $em->getRepository(WellnessProfessional::class)->findOneBy(['user' => $wpOne->getUserId()]);

        //Get relationships from the user
        $relationshipsInit = $wpOne->relationshipsInitiated->getValues();
        $relationshipsReq = $wpOne->relationshipsRequested->getValues();

        $relationshipsarrayUserOne = array_merge($relationshipsInit, $relationshipsReq);


        //Boolean to see if a relationship was found
        $matchingRelationshipFound = false;

        //Loop through all user relationships and look for any with both these users of type friend
        foreach ($relationshipsarrayUserOne as $rel)
        {
            if (($rel->getUserIdOne() == $wpOne || $rel->getUserIdTwo() == $wpOne) &&
                $rel->getUserIdOne() == $userTwo || $rel->getUserIdTwo() == $userTwo)
            {
                //Check type of relationship and set up variables for return
                if ($rel->getType() == 'client' || $rel->getType() == 'wellness professional')
                {
                    $matchingRelationshipFound = true;
                    $reply['status'] = 'failure';
                    $reply['reason'] = 'Patient relationship already exists';

                }
            }
        }

        if (!$matchingRelationshipFound)
        {

            //If variable has been set then the user is not a valid wellness professional.
            //Will set the status to failure.
            if(!isset($isProf))
            {
                $reply['status'] = 'failure';
                $reply['reason'] = 'You have not been verified as a qualified wellness professional.';
            }
            //Will count the number of client relationships this user has can check that they are not over the limit
            //Will set the status to failure if so.
            elseif(getCountFromDB::countRelationship($wpOne->getUserId(), 'client', $em) >= 10)
            {
                $reply['status'] = 'failure';
                $reply['reason'] = 'Max clients reached.';
            }
            //will count the number of wellness professional relationships the added user has. check that they are not over the limit
            //set the status to failure if they are over the limit
            elseif(getCountFromDB::countRelationship($userTwo->getUserId(), 'wellness professional', $em) >= 10)
            {
                $reply['status'] = 'failure';
                $reply['reason'] = 'Clients wellness professional list is full';
            }
        }

        return $reply;
    }
}