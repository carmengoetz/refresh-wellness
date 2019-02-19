<?php
namespace AppBundle\Services;


use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Relationship;
/**
 * Queries the database to return a list of relationships
 *
 * GetRelationshipFromDB description.
 *
 * @version 1.0
 * @author cst245
 */
class GetRelationshipFromDB
{
    /**
     * Returns a list of the IDs belonging to users added to a relations belonging to
     * the passed in user
     * @param mixed $userID - The user to check on
     * @param mixed $relationshipType - The type of relationship to look for
     * @param EntityManager $em - The entity manager to check the database
     * @param mixed $status - Current status of the relationship initiated, use 'all' for all relationships
     * @return array - 2D Array  of relationships belonging to the passed in user
     */
    public static function listRelationship($userID, $relationshipType, EntityManager $em, $status = 'accepted')
    {
        $qb = $em->createQueryBuilder()
         //Creates a select statement to retrieve matching entries
         ->select('IDENTITY(u.userIdTwo)')
         //Sets the db to check as the Relationship db
         ->from(Relationship::class, 'u')
         //creates a where statement to find the selected userID in the Relationship table
         ->andwhere('u.userIdOne = :userID')
         //creates a where statement to find the selected relationship type in the Relationship
         //table that corresponds to the userID
         ->andWhere('u.type = :rType');

        //check to see if the user is looking for all relationships belonging to that specified user
        //if they are, this section will be skipped
        if($status != 'all')
        {
            //sets the status to be checked for
            $qb->andWhere('u.status = :cStatus')
               ->setParameter('cStatus', $status);
        }

        //assigns the value of userID to the passed in value
        $qb->setParameter('userID', $userID)
         //assigns the value of rType to the passed in value
         ->setParameter('rType', $relationshipType);

        //variable to return
        $relationships = $qb->getQuery()->getArrayResult();

        return $relationships;
    }

    /**
     * Returns a list of userIDs belonging to users that have added the passed
     * in user matching the given relationship type
     * @param mixed $userID - The user to check for relationships
     * @param mixed $relationshipType - The type of relationship to check for
     * @param EntityManager $em - The entity manager to check the db
     * @param mixed $status - The current status of relationship to check for
     * @return array - 2D Array of users that have added the passed in user to a relationship.
     */
    public static function listAddedRelationships($userID, $relationshipType, EntityManager $em, $status = 'accepted')
    {
        $qb = $em->createQueryBuilder()
         //Creates a select statement to retrieve matching entries
         ->select('IDENTITY(u.userIdOne)')
         //Sets the db to check as the Relationship db
         ->from(Relationship::class, 'u')
         //creates a where statement to find the selected userID in the Relationship table
         ->andwhere('u.userIdTwo = :userID')
         //creates a where statement to find the selected relationship type in the Relationship
         //table that corresponds to the userID
         ->andWhere('u.type = :rType');


        //check to see if the user is looking for all relationships belonging to that specified user
        //if they are, this section will be skipped
        if($status != 'all')
        {
            //sets the status to be checked for
            $qb->andWhere('u.status = :cStatus')
                ->setParameter('cStatus', $status);
        }

        //assigns the value of userID to the passed in value
        $qb->setParameter('userID', $userID)
         //assigns the value of rType to the passed in value
         ->setParameter('rType', $relationshipType);

        //variable to return
        $relationships = $qb->getQuery()->getArrayResult();

        return $relationships;
    }
}