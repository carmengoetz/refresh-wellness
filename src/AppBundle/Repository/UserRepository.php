<?php

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;

/**
 * repository to help find users in the database
 */
class UserRepository extends EntityRepository
{
    //current default number of conversations to be loaded per page
    private static $numRecords = 20;

    /**
     * search function takes in a criteria to search by and a page num, an returns
     * an array of possible users to make the criteria
     * @param array $criteria - can be a single string in the array, or a firstname lastname
     * @param mixed $pageNum
     * @return mixed
     */
    public function search(array $criteria, $pageNum)
    {
        //checking if there is more than one item in the array (indicating searching by first and last name)
        if (array_key_exists(1, $criteria))
        {
            //querying the database matching the search criteria to the first and last name and returning
        	return $this->_em->createQuery("SELECT u FROM AppBundle:User u WHERE u.firstName LIKE :criteriaf AND u.lastName LIKE :criterial ORDER BY u.firstName ASC, u.lastName ASC")
                ->setParameters(array("criteriaf" => $criteria[0]."%", "criterial"=> $criteria[1]."%"))
                ->setFirstResult(($pageNum - 1) * $this::$numRecords)
                ->setMaxResults($this::$numRecords)
                ->getResult();
        }
        //only one item in the array to search by
        else
        {
            //querying the database matching the search criteria to the first or last name and returning
            return $this->_em->createQuery("SELECT u FROM AppBundle:User u WHERE u.firstName LIKE :criteriaf OR u.lastName LIKE :criteriaf ORDER BY u.firstName ASC, u.lastName ASC")
                ->setParameter("criteriaf", $criteria[0]."%")
                ->setFirstResult(($pageNum - 1) * $this::$numRecords)
                ->setMaxResults($this::$numRecords)
                ->getResult();
        }

    }

}