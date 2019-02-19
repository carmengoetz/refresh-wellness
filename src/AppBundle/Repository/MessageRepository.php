<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * MessageRepository is used to query the database for messages in a specific conversation between 2 users
 */
class MessageRepository extends EntityRepository
{
    //current default amount of messages to be loaded per page
    private static $numRecords = 20;

    /**
     * Function that takes in the logged in user object, another user object, and a page num
     * and returns entries in the message table between those two users and only a single page
     * worth of entries (specified in $numRecords).
     * 
     * @param User $loggedInUser 
     * @param User $otherUser 
     * @param mixed $pageNum 
     * @return mixed
     */
    public function findByPageNum(User $loggedInUser, User $otherUser, $pageNum)
    {
        //Get IDs from the loggedInUser and the otherUser
        $loggedInUserID = $loggedInUser->getUserId();
        $otherUserID = $otherUser->getUserId();

        //Set the parameters array to pass in to the query
        $parameters = array('loggedInUserID' => $loggedInUserID, 'otherUserID' => $otherUserID);

        //Return the query results
        return $this->_em->createQuery("SELECT m FROM AppBundle:Message m WHERE m.sender = :loggedInUserID AND m.receiver = :otherUserID OR " .
            "m.sender = :otherUserID AND m.receiver = :loggedInUserID ORDER BY m.dateSent DESC, m.timeSent DESC")
            ->setParameters($parameters)
            ->setFirstResult(($pageNum - 1) * $this::$numRecords)
            ->setMaxResults($this::$numRecords)
            ->getResult();

    }

}

