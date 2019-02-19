<?php

namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Respondent;
use AppBundle\Entity\Relationship;
use AppBundle\Entity\Wellness;
use AppBundle\Entity\WellnessProfessional;

/**
 * Fixtures to test getting wellness statistics in aggregate for a
 * wellness professional's patients.
 *
 * @version 1.0
 * @author cst231
 */
class WPAggStatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Only load fixtures if a user with this id does not already exist
        if(empty($manager->getRepository(User::class)->findOneBy(array('userID' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC1'))))
        {
            /* Make 5 Users */

            #region WP #1
            //WP #1 - Will have 3 patients
            $user1 = new User();
            $user1->setUserId('4FAC2763-9FC0-FC21-4762-42330CEB9BC1');
            $user1->setFirstName('User');
            $user1->setLastName('One');
            $user1->setEmail('cst.project5.refresh+test41@gmail.com');
            $user1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user1->setDate(new \DateTime('1969-11-28'));
            $user1->setCountry('CA');
            $user1->setCity('Saskatoon');
            $user1->setStatus('active');
            $user1->setIsActive(true);
            $manager->persist($user1);

            //Make them a wellness pro
            $wp1 = new WellnessProfessional();
            $wp1->setUser($user1);
            $wp1->setPracticeName("Bob's Therapy Emporium");
            $wp1->setContactNumber('3061234567');
            $wp1->setContactEmail("bob@therapyemporium.ca");
            $manager->persist($wp1);

            #endregion //WP #1

            #region WP #2
            //WP #2 - Will have no patients
            $user2 = new User();
            $user2->setUserId('4FAC2763-9FC0-FC21-4762-42330CEB9BC2');
            $user2->setFirstName('User');
            $user2->setLastName('Two');
            $user2->setEmail('cst.project5.refresh+test42@gmail.com');
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Saskatoon');
            $user2->setStatus('active');
            $user2->setIsActive(true);
            $manager->persist($user2);

            //Make them a wellness pro
            $wp2 = new WellnessProfessional();
            $wp2->setUser($user2);
            $wp2->setPracticeName("Neil's Therapy Emporium");
            $wp2->setContactNumber('3061234567');
            $wp2->setContactEmail("bob@therapyemporium.ca");
            $manager->persist($wp2);

            #endregion //WP #2

            #region Patient #1
            //Patient #1
            $user3 = new User();
            $user3->setUserId('4FAC2763-9FC0-FC21-4762-42330CEB9BC3');
            $user3->setFirstName('User');
            $user3->setLastName('Three');
            $user3->setEmail('cst.project5.refresh+test43@gmail.com');
            $user3->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user3->setDate(new \DateTime('1969-11-28'));
            $user3->setCountry('CA');
            $user3->setCity('Saskatoon');
            $user3->setStatus('active');
            $user3->setIsActive(true);
            $manager->persist($user3);

            //Make patient #1 respondent
            $respondent1 = new Respondent();
            $respondent1->setUser($user3);
            $respondent1->setAtRisk(true);
            $respondent1->setMentalState("Hungry");
            $respondent1->setStressLevel(63650);
            $respondent1->setEmergencyContact("IHateCats");
            $respondent1->setDoctor("User1");
            $manager->persist($respondent1);

            //Give patient #1 2 wellness records
            $wellnessRec1 = new Wellness();
            $wellnessRec1->setRespondent($respondent1);
            $wellnessRec1->setMood(2);
            $wellnessRec1->setEnergy(3);
            $wellnessRec1->setThoughts(4);
            $wellnessRec1->setSleep(2);
            $wellnessRec1->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec1);

            $wellnessRec2 = new Wellness();
            $wellnessRec2->setRespondent($respondent1);
            $wellnessRec2->setMood(4);
            $wellnessRec2->setEnergy(4);
            $wellnessRec2->setThoughts(4);
            $wellnessRec2->setSleep(4);
            $wellnessRec2->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec2);

            //Add WP relationship with WP #1
            $relationship1 = new Relationship();
            $relationship1->setUserIdOne($user3);
            $relationship1->setUserIdTwo($user1);
            $relationship1->setType("wellness professional");
            $relationship1->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $relationship1->setStatus("active");
            $relationship1->setRelationshipId();
            $manager->persist($relationship1);

            #endregion //Patient #1

            #region Patient #2
            //Patient #2
            $user4 = new User();
            $user4->setUserId('4FAC2763-9FC0-FC21-4762-42330CEB9BC4');
            $user4->setFirstName('User');
            $user4->setLastName('Four');
            $user4->setEmail('cst.project5.refresh+test44@gmail.com');
            $user4->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user4->setDate(new \DateTime('1969-11-28'));
            $user4->setCountry('CA');
            $user4->setCity('Saskatoon');
            $user4->setStatus('active');
            $user4->setIsActive(true);
            $manager->persist($user4);

            //Make patient #2 respondent
            $respondent2 = new Respondent();
            $respondent2->setUser($user4);
            $respondent2->setAtRisk(true);
            $respondent2->setMentalState("Hungry");
            $respondent2->setStressLevel(63650);
            $respondent2->setEmergencyContact("IHateCats");
            $respondent2->setDoctor("User1");
            $manager->persist($respondent2);

            //Give patient #2 2 wellness records
            $wellnessRec1 = new Wellness();
            $wellnessRec1->setRespondent($respondent2);
            $wellnessRec1->setMood(3);
            $wellnessRec1->setEnergy(4);
            $wellnessRec1->setThoughts(5);
            $wellnessRec1->setSleep(3);
            $wellnessRec1->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec1);

            $wellnessRec2 = new Wellness();
            $wellnessRec2->setRespondent($respondent2);
            $wellnessRec2->setMood(5);
            $wellnessRec2->setEnergy(5);
            $wellnessRec2->setThoughts(5);
            $wellnessRec2->setSleep(5);
            $wellnessRec2->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec2);

            //Add WP relationship with WP #1
            $relationship2 = new Relationship();
            $relationship2->setUserIdOne($user4);
            $relationship2->setUserIdTwo($user1);
            $relationship2->setType("wellness professional");
            $relationship2->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $relationship2->setStatus("active");
            $relationship2->setRelationshipId();
            $manager->persist($relationship2);

            #endregion //Patient #2

            #region Patient #3
            //Patient #3
            $user5 = new User();
            $user5->setUserId('4FAC2763-9FC0-FC21-4762-42330CEB9BC5');
            $user5->setFirstName('User');
            $user5->setLastName('Five');
            $user5->setEmail('cst.project5.refresh+test45@gmail.com');
            $user5->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user5->setDate(new \DateTime('1969-11-28'));
            $user5->setCountry('CA');
            $user5->setCity('Saskatoon');
            $user5->setStatus('active');
            $user5->setIsActive(true);
            $manager->persist($user5);

            //Make patient #3 respondent
            $respondent3 = new Respondent();
            $respondent3->setUser($user5);
            $respondent3->setAtRisk(true);
            $respondent3->setMentalState("Hungry");
            $respondent3->setStressLevel(63650);
            $respondent3->setEmergencyContact("IHateCats");
            $respondent3->setDoctor("User1");
            $manager->persist($respondent3);

            //Give patient #3 2 wellness records
            $wellnessRec1 = new Wellness();
            $wellnessRec1->setRespondent($respondent3);
            $wellnessRec1->setMood(4);
            $wellnessRec1->setEnergy(5);
            $wellnessRec1->setThoughts(6);
            $wellnessRec1->setSleep(4);
            $wellnessRec1->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec1);

            $wellnessRec2 = new Wellness();
            $wellnessRec2->setRespondent($respondent3);
            $wellnessRec2->setMood(6);
            $wellnessRec2->setEnergy(6);
            $wellnessRec2->setThoughts(6);
            $wellnessRec2->setSleep(6);
            $wellnessRec2->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec2);

            //Add WP relationship with WP #1
            $relationship3 = new Relationship();
            $relationship3->setUserIdOne($user5);
            $relationship3->setUserIdTwo($user1);
            $relationship3->setType("wellness professional");
            $relationship3->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $relationship3->setStatus("active");
            $relationship3->setRelationshipId();
            $manager->persist($relationship3);
            #endregion //Patient #3

            $manager->flush();

        }
    }

    public function unload(ObjectManager $manager)
    {
        #region WP Aggregate Relationships

        //Remove relationship between user1 and user3
        $relationship = $manager->getRepository(Relationship::class)
            ->findOneBy(array('relationshipId' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC3:4FAC2763-9FC0-FC21-4762-42330CEB9BC1:wellnessprofessional'));
        if(!empty($relationship))
        {
            $manager->remove($relationship);
        }

        //Remove relationship between user1 and user4
        $relationship = $manager->getRepository(Relationship::class)
            ->findOneBy(array('relationshipId' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC4:4FAC2763-9FC0-FC21-4762-42330CEB9BC1:wellnessprofessional'));
        if(!empty($relationship))
        {
            $manager->remove($relationship);
        }

        //Remove relationship between user1 and user5
        $relationship = $manager->getRepository(Relationship::class)
            ->findOneBy(array('relationshipId' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC5:4FAC2763-9FC0-FC21-4762-42330CEB9BC1:wellnessprofessional'));
        if(!empty($relationship))
        {
            $manager->remove($relationship);
        }

        #endregion

        #region Remove Wellness Records

        //Remove wellness records
        $wellnessRecs = $manager->getRepository(Wellness::class)
            ->findBy(array('date'=>date("Y-m-d")));

        foreach ($wellnessRecs as $rec)
        {
        	if(!empty($rec))
            {
                $manager->remove($rec);
            }
        }

        #endregion

        #region WP Aggregate Respondents

        //Remove respondent from user 3
        $respondent = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC3'));

        if (!empty($respondent))
        {
        	$manager->remove($respondent);
        }

        //Remove respondent from user 4
        $respondent = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC4'));

        if (!empty($respondent))
        {
        	$manager->remove($respondent);
        }

        //Remove respondent from user 5
        $respondent = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC5'));

        if (!empty($respondent))
        {
        	$manager->remove($respondent);
        }

        #endregion

        #region WP Aggregate WPs

        //Remove WP #1
        $wp = $manager->getRepository(WellnessProfessional::class)
            ->findOneBy(array('user'=>'4FAC2763-9FC0-FC21-4762-42330CEB9BC1'));

        if(!empty($wp))
        {
            $manager->remove($wp);
        }

        //Remove WP #2
        $wp = $manager->getRepository(WellnessProfessional::class)
            ->findOneBy(array('user'=>'4FAC2763-9FC0-FC21-4762-42330CEB9BC2'));

        if(!empty($wp))
        {
            $manager->remove($wp);
        }

        #endregion

        #region WP Aggregate Users

        //Remove user1
        $user = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC1'));

        if(!empty($user))
        {
            $manager->remove($user);
        }

        //Remove user2
        $user = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC2'));

        if(!empty($user))
        {
            $manager->remove($user);
        }

        //Remove user3
        $user = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC3'));

        if(!empty($user))
        {
            $manager->remove($user);
        }

        //Remove user4
        $user = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC4'));

        if(!empty($user))
        {
            $manager->remove($user);
        }

        //Remove user5
        $user = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '4FAC2763-9FC0-FC21-4762-42330CEB9BC5'));

        if(!empty($user))
        {
            $manager->remove($user);
        }

        #endregion

        $manager->flush();
    }
}