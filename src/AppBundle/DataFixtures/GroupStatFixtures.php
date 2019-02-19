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

class GroupStatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Create these users for the functional tests

        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9GR6'))))
        {


            $user2 = new User();
            $user2->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9GR6');
            $user2->setFirstName('User');
            $user2->setLastName('Two');
            $user2->setEmail('groupcontrollertest@yahoo.ca');
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

    public function loadGroup(ObjectManager $manager)
    {
        if (empty($manager->getRepository(Group::class)->findOneBy(array('groupID' => '1'))))
        {
            $group1 = new Group();
            $group1->setgroupId('1');
            $group1->setGroupName('Project Refresh');
            $group1->setGroupDesc('This is for testing purposes');
            $group1->setGroupType("standard");
            $manager->persist($group1);

            $manager->flush();
        }
    }



    public function unload(ObjectManager $manager)
    {

        $user2 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9GR6'));

        if (!empty($user2))
        {
            $manager->remove($user2);
        }
        $resp1 = $manager->getRepository(Respondent::class)
            ->findOneBy(array('user' => '1FAC2763-9FC0-FC21-4762-42330CEB9GR6'));


        if (!empty($resp1))
        {
            $manager->remove($resp1);
        }

        $manager->flush();

    }

    public function unloadGroup(ObjectManager $manager)
    {
        $group1 = $manager->getRepository(Group::class)
            ->findOneBy(array('groupID' => '1'));

        if (!empty($group1))
        {
            $manager->remove($group1);
        }

        $manager->flush();
    }

}