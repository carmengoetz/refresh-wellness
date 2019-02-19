<?php
namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Relationship;
use AppBundle\Entity\WellnessProfessional;

/**
 * Fixtures to test a user sending a message.
 *
 * @version 1.0
 * @author cst231
 */
class ProfileFixtures extends Fixture
{
    /**
     * Load the fixtures we want to use.
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        //Only load fixtures if a user with this id does not already exist
        if(empty($manager->getRepository(User::class)->findOneBy(array('userID' => 'bucky-barnes'))))
        {
            /* Make 6 users */
            //The user who will be viewing the messages/conversations
            $bucky = new User();
            $bucky->setUserId('bucky-barnes');
            $bucky->setFirstName('Bucky');
            $bucky->setLastName('Barnes');
            $bucky->setEmail('profileBucky@gmail.com');
            $bucky->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $bucky->setDate(new \DateTime('1969-11-28'));
            $bucky->setCountry('CA');
            $bucky->setCity('Saskatoon');
            $bucky->setStatus('active');
            $bucky->setIsActive(true);
            $manager->persist($bucky);

            $steve = new User();
            $steve->setUserId('steve-rogers');
            $steve->setFirstName('Steve');
            $steve->setLastName('Rogers');
            $steve->setEmail('profileSteve@gmail.com');
            $steve->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $steve->setDate(new \DateTime('1969-11-28'));
            $steve->setCountry('CA');
            $steve->setCity('Saskatoon');
            $steve->setStatus('active');
            $steve->setIsActive(true);
            $manager->persist($steve);

            $bruce = new User();
            $bruce->setUserId('bruce-banner');
            $bruce->setFirstName('Bruce');
            $bruce->setLastName('Banner');
            $bruce->setEmail('profileBruce@gmail.com');
            $bruce->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $bruce->setDate(new \DateTime('1969-11-28'));
            $bruce->setCountry('CA');
            $bruce->setCity('Saskatoon');
            $bruce->setStatus('active');
            $bruce->setIsActive(true);
            $manager->persist($bruce);

            $relationship1 = new Relationship();
            $relationship1->setUserIdOne($bucky);
            $relationship1->setUserIdTwo($bruce);
            $relationship1->setType("friend");
            $relationship1->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $relationship1->setStatus("active");
            $relationship1->setRelationshipId();
            $manager->persist($relationship1);
            $bruce->setNumFriends(1);
            $bucky->setNumFriends(1);
            $manager->persist($bruce);

            $tony = new User();
            $tony->setUserId('tony-stark');
            $tony->setFirstName('Tony');
            $tony->setLastName('Stark');
            $tony->setEmail('profileTony@gmail.com');
            $tony->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $tony->setDate(new \DateTime('1969-11-28'));
            $tony->setCountry('CA');
            $tony->setCity('Saskatoon');
            $tony->setStatus('active');
            $tony->setIsActive(true);
            $manager->persist($tony);

            $relationship2 = new Relationship();
            $relationship2->setUserIdOne($tony);
            $relationship2->setUserIdTwo($bucky);
            $relationship2->setType("support");
            $relationship2->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $relationship2->setStatus("active");
            $relationship2->setRelationshipId();
            $manager->persist($relationship2);
            $bucky->setNumSupportees(1);
            $tony->setNumSupporters(1);
            $manager->persist($tony);

            $natasha = new User();
            $natasha->setUserId('natasha-romanov');
            $natasha->setFirstName('Natasha');
            $natasha->setLastName('Romanov');
            $natasha->setEmail('profileNatasha@gmail.com');
            $natasha->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $natasha->setDate(new \DateTime('1969-11-28'));
            $natasha->setCountry('CA');
            $natasha->setCity('Saskatoon');
            $natasha->setStatus('active');
            $natasha->setIsActive(true);
            $manager->persist($natasha);

            $wp1 = new WellnessProfessional();
            $wp1->setUser($natasha);
            $wp1->setPracticeName("Black Widow Orphanage");
            $wp1->setContactNumber('3061234567');
            $wp1->setContactEmail("profileNatasha@gmail.com");
            $manager->persist($wp1);

            $vision = new User();
            $vision->setUserId('vision-jones');
            $vision->setFirstName('Vision');
            $vision->setLastName('Jones');
            $vision->setEmail('profileVision@gmail.com');
            $vision->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $vision->setDate(new \DateTime('1969-11-28'));
            $vision->setCountry('CA');
            $vision->setCity('Saskatoon');
            $vision->setStatus('active');
            $vision->setIsActive(true);
            $manager->persist($vision);

            $wp2 = new WellnessProfessional();
            $wp2->setUser($vision);
            $wp2->setPracticeName("Victims of Ultron Support Group");
            $wp2->setContactNumber('3061234567');
            $wp2->setContactEmail("profileVision@gmail.com");
            $manager->persist($wp2);

            $relationship3 = new Relationship();
            $relationship3->setUserIdOne($bucky);
            $relationship3->setUserIdTwo($vision);
            $relationship3->setType("wellness professional");
            $relationship3->setDateStarted(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
            $relationship3->setStatus("active");
            $relationship3->setRelationshipId();
            $manager->persist($relationship3);

            //Commit to the database
            $manager->flush();
        }
    }

    /**
     * Remove all records and changes to the database.
     * @param ObjectManager $manager
     */
    public function unload(ObjectManager $manager)
    {
        //remove all relationships
        $relationships = $manager->getRepository(Relationship::class)->findAll();
        foreach ($relationships as $relationship)
        {
        	$manager->remove($relationship);
        }

        //remove all wps
        $wps = $manager->getRepository(WellnessProfessional::class)->findAll();
        foreach ($wps as $wp)
        {
        	$manager->remove($wp);
        }

        //remove all users
        $users = $manager->getRepository(User::class)->findAll();
        foreach ($users as $user)
        {
        	$manager->remove($user);
        }

        $manager->flush();
    }
}