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

class WellnessFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Create these users for the functional tests

        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BW3'))))
        {
            $user1 = new User();
            $user1->setUserId('1FAC2763-9FC0-FC21-4762-42330CEB9BW3');
            $user1->setFirstName('User');
            $user1->setLastName('One');
            $user1->setEmail('cst.project5.refresh+WellnessRecord@gmail.com');
            $user1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user1->setDate(new \DateTime('1969-11-28'));
            $user1->setCountry('CA');
            $user1->setCity('Saskatoon');
            $user1->setStatus('active');
            $user1->setIsActive(true);
            $manager->persist($user1);

            $respondent = new Respondent();
            $respondent->setUser($user1);
            $respondent->setAtRisk(true);
            $respondent->setMentalState("Hungry");
            $respondent->setStressLevel(63650);
            $respondent->setEmergencyContact("IHateCats");
            $respondent->setDoctor("Caesar Mulan");
            $manager->persist($respondent);

            $manager->flush();
        }

    }


    public function unload(ObjectManager $manager)
    {
        $user1 = $manager->getRepository(User::class)
            ->findOneBy(array('userID' => '1FAC2763-9FC0-FC21-4762-42330CEB9BW3'));

        if (!empty($user1))
        {
            $manager->remove($user1);
        }

        $resp1 = $manager->getRepository(Respondent::class)
           ->findOneBy(array('user' => '1FAC2763-9FC0-FC21-4762-42330CEB9BW3'));


        if (!empty($resp1))
        {
            $manager->remove($resp1);
        }

        $manager->flush();
    }

}