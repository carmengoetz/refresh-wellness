<?php

namespace AppBundle\Services;

use AppBundle\Entity\Conversation;
use AppBundle\Entity\Message;
use AppBundle\Entity\User;

/**
 * Class that contains functions to convert various object lists into an assoc.
 * array that with our desired property names.
 *
 * @version 1.0
 * @author cst231
 */
class ConvertToArray
{
    /**
     *  Service to look through a relationship array and check to see if any relationship types are in existence - used for profiles mostly
     * @param mixed $relationshipsarray
     * @param mixed $userlogin
     * @return integer[]
     */
    public function convertToArrayProfileRelationships($relationshipsarray, $userlogin)
    {
        $isFriend = 0;
        $isSupporter = 0;
        $isSupportee = 0;
        $isWellnessProRel = 0;

        //Loop through all user relationships
        foreach ($relationshipsarray as $rel)
        {
            if ($rel->getUserIdOne() == $userlogin || $rel->getUserIdTwo() == $userlogin)
            {
                //Check type of relationship and set up variables for return
                switch($rel->getType())
                {
                    case "friend":
                        $isFriend = 1;
                        break;
                    case "support":
                        if ( $rel->getUserIdOne() == $userlogin)
                        {
                            $isSupporter = 1;
                        }
                        if ($rel->getUserIdTwo() == $userlogin)
                        {
                            $isSupportee = 1;
                        }
                        break;
                    case "wellness professional":
                    case "client":
                        $isWellnessProRel = 1;
                }
            }
        }

        return array(
                    "isFriend" => $isFriend,
                    "isSupporter" => $isSupporter,
                    "isSupportee" => $isSupportee,
                    "isWellnessProRel" => $isWellnessProRel
               );

    }


    /**
     * Returns an assoc array with a list of all the conversations that the logged in user has.
     * Takes the list of conversations passed in and translates them into the properties we want.
     */
    public function convertToArrayConversations(User $loggedInUser, $conversationList)
    {
        $responseArray = array();

        //Loop through all conversations
        foreach ($conversationList as $conversation)
        {
            $userName = '';
            $userID = '';

            //Get userID and userName of the other user
            if ($loggedInUser->getUserId() == $conversation->getUserOneID()->getUserId())
            {
                $userID = $conversation->getUserTwoID()->getUserId();
                $userName = $conversation->getUserTwoID()->getName();
            }
            else
            {
                $userID = $conversation->getUserOneID()->getUserId();
                $userName = $conversation->getUserOneID()->getName();
            }

            //Get the last message
            $message = $conversation->getLastMessage();

            //Instantiate lastMessage info array
            $lastMessageInfo = array();

            //Check to see if message is null
            if ($message != null)
            {
                $lastMessageInfo['date'] = date_format($message->getDateSent(), "Y-m-d");
                $lastMessageInfo['time'] = date_format($message->getTimeSent(), "H:i:s");
                $lastMessageInfo['messageContent'] = $message->getMessageContent();
                $lastMessageInfo['messageUserID'] = $message->getSender()->getUserId();
                $lastMessageInfo['isRead'] = $message->getIsRead();
            }

            //Set values for return array from conversation
            $responseArray[] = array(
                'userName' => $userName,
                'userID' => $userID,
                'lastMessage' => $lastMessageInfo
            );
        }

        return $responseArray;
    }

    /**
     * Returns an assoc array with a list of all of the messages between the logged in user and the other user passed in.
     * Takes the list of messages passed in and translates them into the properties we want.
     */
    public function convertToArrayMessages(User $loggedInUser, User $otherUser, $messageList)
    {
        //creating an empty array to hold the messages
        $messageArray = array();
        //setting the userName to the full name of the user the conversation is with
        $userName = $otherUser->getName();

        //looping through the messages
        foreach ($messageList as $message)
        {
            //defaulting the userSent to 0 (logged in user didnt send it)
            $userSent = 0;

            //if logged in user is the sender, set user sent to 1
            if ($message->getSender() == $loggedInUser)
            {
            	$userSent = 1;
            }

            //setting the message array
        	$messageArray[] = array(
                'userSent' => $userSent,
                'date' => date_format($message->getDateSent(), "Y-m-d"),
                'time' => date_format($message->getTimeSent(), "H:i:s"),
                'messageContent' => $message->getMessageContent()
                );
        }

        //Reverse the order of the messages so that this set of up to 20 messages
        //is sent with the latest message at the start of the array
        $messageArray = array_reverse($messageArray);

        //Set up return array
        $responseArray = array(
            'userName' => $userName,
            'messages' => $messageArray
            );

        return $responseArray;
    }

    /**
     * Returns an associative array with a list of all the users that matched the search criteria.
     * @param User $loggedInUser
     * @param mixed $searchList
     */
    public function convertToArraySearch(User $loggedInUser, $searchList)
    {
        //creating an empty array to hold the search results
        $searchArray = array();

        //looping through the messages
        foreach ($searchList as $result)
        {
            //Check to see if the user is a wellness professional
            $wp = $result->getWellnessProfessional();

            //if user found is a wellness professional
            if($wp != null)
            {
                //Create the wellnessPro object with user data
                $searchArray[] = array(
                'type' => "wellnessPro",
                'objectData' => array(
                    'id' => $result->getUserId(),
                    'name' => $result->getName(),
                    'city' => $result->getCity(),
                    'practiceName' => $wp->getPracticeName(),
                    'contactNumber' => $wp->getContactNumber(),
                    'contactEmail' => $wp->getContactEmail(),
                    'website' => $wp->getWebsite()
                    )
                );
            }
            //if the user is a respondent
            else
            {
                //Create the resondent object with user data
        	    $searchArray[] = array(
                    'type' => "respondent",
                    'objectData' => array(
                        'id' => $result->getUserId(),
                        'name' => $result->getName(),
                        'city' => $result->getCity()
                    )
                );
            }
        }
        //return the array
        return $searchArray;
    }
}