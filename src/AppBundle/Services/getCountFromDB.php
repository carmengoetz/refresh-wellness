<?php
namespace AppBundle\Services;

/**
 * Query the database to retrieve a count for relationships
 *
 * getCountFromDB description.
 *
 * @version 1.0
 * @author CST236
 */

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Relationship;
use AppBundle\Services\GetRelationshipFromDB;

class getCountFromDB
{
    /**
     * Returns a count of the relationships belonging to the passed in user
     * @param mixed $userID - The user to check on
     * @param mixed $relationshipType - The type of relationship to look for
     * @param EntityManager $em - The entity manager to check the database
     * @return mixed Count of the relationships
     */
    public static function countRelationship($userID, $relationshipType, EntityManager $em, $status='all')
    {
        //call to the getRelationship service to obtain an array of userID's for the passed in relationship type
        //for the passed in user
        $relationships = GetRelationshipFromDB::listRelationship($userID, $relationshipType, $em, $status);

        $numRel = 0;

        //loops through the array, incrementing the count of relationships for the passed in user
        foreach ($relationships as $relation)
        {
        	$numRel++;
        }


        return $numRel;
    }

    //temporary service until wellness professionals can add patients
    //should be able to remove and call count relationship
    //Can also be modified to prevent issues in areas this was implemented
    public static function countPatients($userID, $relationshipType, EntityManager $em)
    {
        //query databased and count entries returned
        $em->getRepository(Relationship::class);

        //Temporary version to be used until Wellness Professionals can properly add patients
        //Checks the database on User Two instead of One, should be swapped to check on User
        //One once functionality is implemented
        $qb = $em->createQueryBuilder()
                 ->select('COUNT(prof.userIdTwo)')
                 ->from(Relationship::class, 'prof')
                 ->andwhere('prof.userIdTwo = :userID')
                 ->andWhere('prof.type = :rType')
                 ->setParameter('userID', $userID)
                 ->setParameter('rType', $relationshipType);

        //variable to return
        $numPatients = $qb->getQuery()->getSingleScalarResult();

        return $numPatients;
    }

    /** Should be fine to delete this, can just pass the status into the count to say pending. Default is set to all
     * Returns a count of the relationships currently pending belonging to the passed in user
     * @param mixed $userID - The user to check on
     * @param mixed $relationshipType - The type of relationship to look for
     * @param EntityManager $em - The entity manager to check the database
     * @return mixed Count of the relationships
     */
    public static function countRelationshipAddedTo($userID, $relationshipType, EntityManager $em, $status = 'all')
    {
        //calls GetRelationship service to obtain an array to check for relationships that the passed in user
        //has been added to from someone else
        $relationships = GetRelationshipFromDB::listAddedRelationships($userID, $relationshipType, $em, $status);

        $numRel = 0;

        //loops through the array, incrementing the count of relationships for the passed in user
        foreach ($relationships as $relation)
        {
        	$numRel++;
        }

        return $numRel;
    }
}