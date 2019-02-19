<?php

namespace AppBundle\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints\Date;
use AppBundle\Entity\Respondent;
use AppBundle\Entity\Group;
use AppBundle\Entity\WellnessProfessional;
use AppBundle\Entity\Relationship;
use AppBundle\Entity\Wellness;

class CGStatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Create these users for the functional tests

        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9CG7'))))
        {
            $user1 = new User();
            $user1->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9CG7');
            $user1->setFirstName('User');
            $user1->setLastName('One');
            $user1->setEmail('cst.project5.refresh+CG1@gmail.com');
            $user1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user1->setDate(new \DateTime('1969-11-28'));
            $user1->setCountry('CA');
            $user1->setCity('Saskatoon');
            $user1->setStatus('active');
            $user1->setIsActive(true);
            $manager->persist($user1);

            //Add respondents
            $respondent = new Respondent();
            $respondent->setUser($user1);
            $respondent->setAtRisk(true);
            $respondent->setMentalState("Hungry");
            $respondent->setStressLevel(63650);
            $respondent->setEmergencyContact("IHateCats");
            $respondent->setDoctor("Caesar Mulan");
            $manager->persist($respondent);

            //Add wellness records
            $wellnessRec1 = new Wellness();
            $wellnessRec1->setRespondent($respondent);
            $wellnessRec1->setMood(2);
            $wellnessRec1->setEnergy(2);
            $wellnessRec1->setThoughts(2);
            $wellnessRec1->setSleep(2);
            $wellnessRec1->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec1);

            $user2 = new User();
            $user2->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9CG6');
            $user2->setFirstName('User');
            $user2->setLastName('Two');
            $user2->setEmail('cst.project5.refresh+CG2@gmail.com');
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Calgary');
            $user2->setStatus('active');
            $user2->setIsActive(true);
            $manager->persist($user2);

            $user5 = new User();
            $user5->setUserId('2FAC2763-9FC0-FC21-4762-42330CEB9CG5');
            $user5->setFirstName('User');
            $user5->setLastName('Three');
            $user5->setEmail('cst.project5.refresh+CG3@gmail.com');
            $user5->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user5->setDate(new \DateTime('1969-11-28'));
            $user5->setCountry('CA');
            $user5->setCity('Saskatoon');
            $user5->setStatus('active');
            $user5->setIsActive(true);
            $manager->persist($user5);

            /* Updates for wellness pro relationship */
            //Add caregiver relationship
            $relationship = new Relationship();
            $relationship->setUserIdOne($user1);
            $relationship->setUserIdTwo($user2);
            $relationship->setType("support");
            $relationship->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $relationship->setStatus("active");
            $relationship->setRelationshipId();
            $manager->persist($relationship);

            //Add respondents
            $respondent = new Respondent();
            $respondent->setUser($user2);
            $respondent->setAtRisk(true);
            $respondent->setMentalState("Hungry");
            $respondent->setStressLevel(63650);
            $respondent->setEmergencyContact("IHateCats");
            $respondent->setDoctor("Caesar Mulan");
            $manager->persist($respondent);


            $respondent2 = new Respondent();
            $respondent2->setUser($user5);
            $respondent2->setAtRisk(true);
            $respondent2->setMentalState("Dead");
            $respondent2->setStressLevel(0);
            $respondent2->setEmergencyContact("Satan");
            $respondent2->setDoctor("Dr. Phil");
            $manager->persist($respondent2);

            //Add wellness records
            $wellnessRec1 = new Wellness();
            $wellnessRec1->setRespondent($respondent2);
            $wellnessRec1->setMood(2);
            $wellnessRec1->setEnergy(2);
            $wellnessRec1->setThoughts(2);
            $wellnessRec1->setSleep(2);
            $wellnessRec1->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec1);

            //Add wellness records
            $wellnessRec1 = new Wellness();
            $wellnessRec1->setRespondent($respondent);
            $wellnessRec1->setMood(2);
            $wellnessRec1->setEnergy(2);
            $wellnessRec1->setThoughts(2);
            $wellnessRec1->setSleep(2);
            $wellnessRec1->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec1);

            //Add wellness records
            //$wellnessRec2 = new Wellness();
            //$wellnessRec2->setRespondent($respondent);
            //$wellnessRec2->setMood(2);
            //$wellnessRec2->setEnergy(2);
            //$wellnessRec2->setThoughts(2);
            //$wellnessRec2->setSleep(2);
            //$wellnessRec2->setDate(date("Y-m-d"));
            //$manager->persist($wellnessRec2);

            $user10 = new User();
            $user10->setUserId('user-one-whatever');
            $user10->setFirstName('User');
            $user10->setLastName('One');
            $user10->setEmail('userOneWellnessStuff@gmail.com');
            $user10->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user10->setDate(new \DateTime('1969-11-28'));
            $user10->setCountry('CA');
            $user10->setCity('Saskatoon');
            $user10->setStatus('active');
            $user10->setIsActive(true);
            $manager->persist($user10);

            //Add respondents
            $respondent10 = new Respondent();
            $respondent10->setUser($user10);
            $respondent10->setAtRisk(true);
            $respondent10->setMentalState("Hungry");
            $respondent10->setStressLevel(63650);
            $respondent10->setEmergencyContact("IHateCats");
            $respondent10->setDoctor("Caesar Mulan");
            $manager->persist($respondent10);

            //Add wellness records
            $wellnessRec10 = new Wellness();
            $wellnessRec10->setRespondent($respondent10);
            $wellnessRec10->setMood(2);
            $wellnessRec10->setEnergy(2);
            $wellnessRec10->setThoughts(2);
            $wellnessRec10->setSleep(2);
            $wellnessRec10->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec10);

            $user20 = new User();
            $user20->setUserId('new-user-two');
            $user20->setFirstName('User');
            $user20->setLastName('Two');
            $user20->setEmail('userTwoWellnessStuff@gmail.com');
            $user20->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user20->setDate(new \DateTime('1969-11-28'));
            $user20->setCountry('CA');
            $user20->setCity('Calgary');
            $user20->setStatus('active');
            $user20->setIsActive(true);
            $manager->persist($user20);

            /* Updates for wellness pro relationship */
            //Add caregiver relationship
            $relationship10 = new Relationship();
            $relationship10->setUserIdOne($user10);
            $relationship10->setUserIdTwo($user20);
            $relationship10->setType("support");
            $relationship10->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $relationship10->setStatus("active");
            $relationship10->setRelationshipId();
            $manager->persist($relationship10);

            //Add respondents
            $respondent20 = new Respondent();
            $respondent20->setUser($user20);
            $respondent20->setAtRisk(true);
            $respondent20->setMentalState("Hungry");
            $respondent20->setStressLevel(63650);
            $respondent20->setEmergencyContact("IHateCats");
            $respondent20->setDoctor("Caesar Mulan");
            $manager->persist($respondent20);

            $manager->flush();

        }

    }


    public function unload(ObjectManager $manager)
    {
        $user1 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9CG7'));

        if (!empty($user1))
        {
            $manager->remove($user1);
        }


        $user2 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9CG6'));

        if (!empty($user2))
        {
            $manager->remove($user2);
        }

        $resp1 = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '1FAC2763-9FC0-FC21-4762-42330CEB9CG6'));


        if (!empty($resp1))
        {
            $manager->remove($resp1);
        }

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

        //Remove relationship
        $relationship = $manager->getRepository(Relationship::class)
            ->findOneBy(array('relationshipId' => '1FAC2763-9FC0-FC21-4762-42330CEB9CG7:1FAC2763-9FC0-FC21-4762-42330CEB9CG6:support'));
        if(!empty($relationship))
        {
            $manager->remove($relationship);
        }

        //Remove Respondent for user 3
        $respondentTwo = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '2FAC2763-9FC0-FC21-4762-42330CEB9CG5'));

        if (!empty($respondentTwo))
        {
        	$manager->remove($respondentTwo);
        }

        //Remove user3
        $user3 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '2FAC2763-9FC0-FC21-4762-42330CEB9CG5'));

        if(!empty($user3))
        {
            $manager->remove($user3);
        }

        $manager->flush();
    }

}