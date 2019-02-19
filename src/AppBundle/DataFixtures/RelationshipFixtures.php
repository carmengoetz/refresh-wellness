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

/**
 * Unique fixtures for the RelationshipControllerTest
 */
class RelationshipFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7'))))
        {
            $user1 = new User();
            $user1->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9BC7');
            $user1->setFirstName('User');
            $user1->setLastName('One');
            $user1->setEmail('cst.project5.refresh+test1@gmail.com');
            $user1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user1->setDate(new \DateTime('1969-11-28'));
            $user1->setCountry('CA');
            $user1->setCity('Saskatoon');
            $user1->setStatus('active');
            $user1->setIsActive(true);
            $manager->persist($user1);

            $user2 = new User();
            $user2->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9BC6');
            $user2->setFirstName('User');
            $user2->setLastName('Two');
            $user2->setEmail('imtigerwoods@yahoo.ca');
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Calgary');
            $user2->setStatus('active');
            $user2->setIsActive(true);
            $manager->persist($user2);

            $user3 = new User();
            $user3->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9BC8');
            $user3->setFirstName('User');
            $user3->setLastName('Three');
            $user3->setEmail('cst.project5.refresh+test3@gmail.com');
            $user3->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user3->setDate(new \DateTime('1969-11-28'));
            $user3->setCountry('CA');
            $user3->setCity('Winnipeg');
            $user3->setStatus('inactive');
            $user3->setIsActive(false);
            $manager->persist($user3);

            ///******************** ADD FIXTURES FOR WELLNESS QUESTIONS */
            //$resp1 = new Respondent();
            //$resp1->setUser($user2);
            //$resp1->setAtRisk(false);
            //$resp1->setMentalState('gud');
            //$resp1->setStressLevel(5);
            //$resp1->setDoctor("Dr. Feelgud");
            //$resp1->setEmergencyContact('8675309');

            //$manager->persist($resp1);

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

            $manager->flush();
        }

    }

    public function unload(ObjectManager $manager)
    {
        $user1 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7'));

        if (!empty($user1))
        {
            $manager->remove($user1);
        }


        $user2 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC6'));

        if (!empty($user2))
        {
            $manager->remove($user2);
        }

        $user3 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC8'));

        if (!empty($user3))
        {
            $manager->remove($user3);
        }


        //Remove relationship
        $relationship = $manager->getRepository(Relationship::class)
            ->findOneBy(array('relationshipId' => '1FAC2763-9FC0-FC21-4762-42330CEB9BC7:1FAC2763-9FC0-FC21-4762-42330CEB9BC6:support'));
        if(!empty($relationship))
        {
            $manager->remove($relationship);
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