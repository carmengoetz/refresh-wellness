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

class UserStatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Create these users for the functional tests

        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9US7'))))
        {
            $user1 = new User();
            $user1->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9US7');
            $user1->setFirstName('User');
            $user1->setLastName('One');
            $user1->setEmail('userstatcontroller@userone.com');
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
            $user2->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9US6');
            $user2->setFirstName('User');
            $user2->setLastName('Two');
            $user2->setEmail('userstatcontroller@usertwo.com');
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Calgary');
            $user2->setStatus('active');
            $user2->setIsActive(true);
            $manager->persist($user2);


            //Add respondents
            $respondent = new Respondent();
            $respondent->setUser($user2);
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


            $manager->flush();
        }

    }



    public function unload(ObjectManager $manager)
    {
        $user1 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9US7'));

        if (!empty($user1))
        {
            $manager->remove($user1);
        }


        $user2 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9US6'));

        if (!empty($user2))
        {
            $manager->remove($user2);
        }
        $resp1 = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '1FAC2763-9FC0-FC21-4762-42330CEB9US6'));


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



        $manager->flush();
    }


}