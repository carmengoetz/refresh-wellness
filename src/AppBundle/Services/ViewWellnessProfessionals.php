<?php
namespace AppBundle\Services;
/**
 * ViewWellnessProfessionals short summary.
 *
 * ViewWellnessProfessionals description.
 *
 * @version 1.0
 * @author cst233
 */

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\WellnessProfessional;
use AppBundle\Entity\User;
use Doctrine\ORM\Query\Expr\Join;
class ViewWellnessProfessionals
{
    /**
     * Gets a list of wellness professionals based on parameters
     * @param mixed $community
     * @param mixed $pageNum
     * @param EntityManager $em
     * @return array
     */
    public static function generateArray($community, $pageNum, EntityManager $em)
    {

        //create a query builder to look in the db
        $qb = $em->createQueryBuilder();

        //Select query
        $qb->select('DISTINCT wp.practiceName, u.city, wp.contactNumber, wp.contactEmail, wp.website')
            ->from(WellnessProfessional::class, 'wp')
            ->leftJoin(User::class, 'u', 'WITH', 'u.userID = wp.user')
            ->where('u.city = (:comm)')
            ->setParameter('comm', $community)
            ->orderBy('wp.practiceName');

        //Run the query
        $results = $qb->getQuery()->getResult();

        //Count the results
        $numFound = count($results);

        //If none found, return an error
        if ($numFound == 0)
        {
            $response = array("status" => "failure", "message" => "No wellness professionals found in " . $community, "data" => "");
        }
        else
        {
            //If the page num is too big or too small, return an error
            if ( ($pageNum - 1) * 10 > $numFound || $pageNum <= 0)
            {
                $response = array("status" => "failure", "message" => "Invalid page request", "data" => "");
            }
            else
            {
                //Create an array to hold the returned objects
                $objects = array();

                //If the numfound is bigger than the page number times 10 then just get ten results
                if ($numFound > $pageNum * 10)
                {
                    for ($i = ($pageNum- 1) * 10; $i < $pageNum * 10; $i++)
                    {
                        $objects[] = array('type' => 'WellnessProfessional', 'objectData' => $results[$i]);
                    }

                }
                //Otherwise, get whatever results remain
                else
                {
                    for ($i = ($pageNum- 1) * 10; $i < $numFound; $i++)
                    {
                        $objects[] = array('type' => 'WellnessProfessional', 'objectData' => $results[$i]);
                    }
                }
                $response = array("status" => "success", "message" => "", "data" => array("totalFound" => $numFound, "pageNumber" => $pageNum, "objects" => $objects));
            }
        }


        return $response;
    }
}