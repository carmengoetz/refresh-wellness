<?php
namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Wellness;
use AppBundle\Entity\Respondent;
use AppBundle\Entity\WellnessProfessional;

/**
 * Fixtures to test a user sending a message.
 *
 * @version 1.0
 * @author cst231
 */
class ViewWellnessFixtures extends Fixture
{
    /**
     * Load the fixtures we want to use.
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        //Only load fixtures if a user with this id does not already exist
        if(empty($manager->getRepository(User::class)->findOneBy(array('userID' => 'sherlock-holmes'))))
        {
            /* Make 3 users */
            //the user with no wellness questions but needs to answer
            $sherlock = new User();
            $sherlock->setUserId('sherlock-holmes');
            $sherlock->setFirstName('Sherlock');
            $sherlock->setLastName('Holmes');
            $sherlock->setEmail('wellnessSherlock@gmail.com');
            $sherlock->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $sherlock->setDate(new \DateTime('1981-01-06'));
            $sherlock->setCountry('EN');
            $sherlock->setCity('London');
            $sherlock->setStatus('active');
            $sherlock->setIsActive(true);
            $manager->persist($sherlock);

            $respondent = new Respondent();
            $respondent->setUser($sherlock);
            $respondent->setAtRisk(true);
            $respondent->setMentalState("High-functioning sociopath");
            $respondent->setStressLevel(63650);
            $respondent->setEmergencyContact("Mycroft Holmes");
            $respondent->setDoctor("John Watson");
            $manager->persist($respondent);

            //The wellness professional
            $john = new User();
            $john->setUserId('john-watson');
            $john->setFirstName('John');
            $john->setLastName('Watson');
            $john->setEmail('wellnessJohn@gmail.com');
            $john->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $john->setDate(new \DateTime('1981-03-31'));
            $john->setCountry('EN');
            $john->setCity('London');
            $john->setStatus('active');
            $john->setIsActive(true);
            $manager->persist($john);

            $wp1 = new WellnessProfessional();
            $wp1->setUser($john);
            $wp1->setPracticeName("Saint Bart's Veteran Rehab");
            $wp1->setContactNumber('3061234567');
            $wp1->setContactEmail("wellnessJohn@gmail.com");
            $wp1->setWebsite('http://www.johnwatsonblog.co.uk');
            $manager->persist($wp1);

            //the user with wellness answered today
            $mycroft = new User();
            $mycroft->setUserId('mycroft-holmes');
            $mycroft->setFirstName('Mycroft');
            $mycroft->setLastName('Holmes');
            $mycroft->setEmail('wellnessMycroft@gmail.com');
            $mycroft->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $mycroft->setDate(new \DateTime('1974-10-17'));
            $mycroft->setCountry('EN');
            $mycroft->setCity('London');
            $mycroft->setStatus('active');
            $mycroft->setIsActive(true);
            $manager->persist($mycroft);

            $respondent = new Respondent();
            $respondent->setUser($mycroft);
            $respondent->setAtRisk(true);
            $respondent->setMentalState("OCD");
            $respondent->setStressLevel(63650);
            $respondent->setEmergencyContact("Mycroft Holmes");
            $respondent->setDoctor("John Watson");
            $manager->persist($respondent);

            $wellnessRec1 = new Wellness();
            $wellnessRec1->setRespondent($respondent);
            $wellnessRec1->setMood(2);
            $wellnessRec1->setEnergy(2);
            $wellnessRec1->setThoughts(2);
            $wellnessRec1->setSleep(2);
            $wellnessRec1->setDate(date("Y-m-d"));
            $manager->persist($wellnessRec1);


            //Commit to the database
            $manager->flush();
        }
    }

}