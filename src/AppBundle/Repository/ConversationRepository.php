<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ConversationRepository used to query the database for conversations that a given user is a part of
 */
class ConversationRepository extends EntityRepository
{
    //current default number of conversations to be loaded per page
    private static $numRecords = 20;

    /**
     * Function that takes in the logged in user object and a page number and returns all entries in the 
     * conversation table involving the logged in user, and only a single page worth of records 
     * (specified in $numRecords).
     * 
     * @param User $loggedInUser 
     * @param mixed $pageNum 
     * @return array
     */
    public function findByPageNum(User $loggedInUser, $pageNum)
    {
        //Get the logged in user's id
        $loggedInUserID = $loggedInUser->getUserId();
        //Create the query
        $query= $this->_em->createQuery("SELECT c FROM AppBundle:Conversation c JOIN c.lastMessage m WHERE c.userOneID = :userOneID OR c.userTwoID = :userOneID ORDER BY m.dateSent DESC, m.timeSent DESC")
            ->setParameter("userOneID", $loggedInUserID)
            ->setFirstResult(($pageNum - 1) * $this::$numRecords)
            ->setMaxResults($this::$numRecords);
        //Insert the query into a paginator to deal with joins more effectively
        $paginator = new Paginator($query);

        return $paginator->getQuery()->getResult();
    }

}

