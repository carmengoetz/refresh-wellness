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
use AppBundle\Entity\GroupMember;

/**
 * Fixture to create users and groups to be searched for
 *
 * @version 1.0
 * @author cst236
 */
class UserSearchFixtures extends Fixture
{
    // load the fixtures to the database
    // unload will be handled by wiping the database
    public function load(ObjectManager $manager)
    {
        if (empty($manager->getRepository(User::class)->findOneBy(array('userID' => 'homerSimpsonID'))))
        {
            #region User For Login Homer
            $user1 = new User();
            $user1->setUserId('homerSimpsonID');
            $user1->setFirstName('Homer');
            $user1->setLastName('Simpson');
            $user1->setEmail('HomerSimpson@gmail.com');
            $user1->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user1->setDate(new \DateTime('1969-11-28'));
            $user1->setCountry('CA');
            $user1->setCity('Springfield');
            $user1->setStatus('active');
            $user1->setIsActive(true);
            $manager->persist($user1);

            //Make member a respondent
            $respondent1 = new Respondent();
            $respondent1->setUser($user1);
            $respondent1->setAtRisk(true);
            $respondent1->setMentalState("Hungry");
            $respondent1->setStressLevel(4);
            $respondent1->setEmergencyContact("Bob");
            $respondent1->setDoctor("Travis");
            $manager->persist($respondent1);

            //Give member 2 wellness records
            $wellnessHomer1 = new Wellness();
            $wellnessHomer1->setRespondent($respondent1);
            $wellnessHomer1->setMood(2);
            $wellnessHomer1->setEnergy(3);
            $wellnessHomer1->setThoughts(4);
            $wellnessHomer1->setSleep(2);
            $wellnessHomer1->setDate(date("Y-m-d"));
            $manager->persist($wellnessHomer1);

            $wellnessHomer2 = new Wellness();
            $wellnessHomer2->setRespondent($respondent1);
            $wellnessHomer2->setMood(4);
            $wellnessHomer2->setEnergy(4);
            $wellnessHomer2->setThoughts(4);
            $wellnessHomer2->setSleep(4);
            $wellnessHomer2->setDate(date("Y-m-d"));
            $manager->persist($wellnessHomer2);

            #endregion

            #region User Marge - Respondent
            $user2 = new User();
            $user2->setUserId('margeSimpsonID');
            $user2->setFirstName('Marge');
            $user2->setLastName('Simpson');
            $user2->setEmail('MargeSimpson@gmail.com');
            $user2->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user2->setDate(new \DateTime('1969-11-28'));
            $user2->setCountry('CA');
            $user2->setCity('Springfield');
            $user2->setStatus('active');
            $user2->setIsActive(true);
            $manager->persist($user2);

            //Make member a respondent
            $respondent2 = new Respondent();
            $respondent2->setUser($user2);
            $respondent2->setAtRisk(true);
            $respondent2->setMentalState("Hungry");
            $respondent2->setStressLevel(4);
            $respondent2->setEmergencyContact("Bob");
            $respondent2->setDoctor("Travis");
            $manager->persist($respondent2);

            //Give member 2 wellness records
            $wellnessMarge1 = new Wellness();
            $wellnessMarge1->setRespondent($respondent2);
            $wellnessMarge1->setMood(2);
            $wellnessMarge1->setEnergy(3);
            $wellnessMarge1->setThoughts(4);
            $wellnessMarge1->setSleep(2);
            $wellnessMarge1->setDate(date("Y-m-d"));
            $manager->persist($wellnessMarge1);

            $wellnessMarge2 = new Wellness();
            $wellnessMarge2->setRespondent($respondent2);
            $wellnessMarge2->setMood(4);
            $wellnessMarge2->setEnergy(4);
            $wellnessMarge2->setThoughts(4);
            $wellnessMarge2->setSleep(4);
            $wellnessMarge2->setDate(date("Y-m-d"));
            $manager->persist($wellnessMarge2);

            #endregion

            #region User Bart - Respondent
            $user3 = new User();
            $user3->setUserId('bartSimpsonID');
            $user3->setFirstName('Bart');
            $user3->setLastName('Simpson');
            $user3->setEmail('BartSimpson@gmail.com');
            $user3->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user3->setDate(new \DateTime('1969-11-28'));
            $user3->setCountry('CA');
            $user3->setCity('Springfield');
            $user3->setStatus('active');
            $user3->setIsActive(true);
            $manager->persist($user3);

            //Make member a respondent
            $respondent3 = new Respondent();
            $respondent3->setUser($user3);
            $respondent3->setAtRisk(true);
            $respondent3->setMentalState("Hungry");
            $respondent3->setStressLevel(4);
            $respondent3->setEmergencyContact("Bob");
            $respondent3->setDoctor("Travis");
            $manager->persist($respondent3);

            //Give member 2 wellness records
            $wellnessBart1 = new Wellness();
            $wellnessBart1->setRespondent($respondent3);
            $wellnessBart1->setMood(2);
            $wellnessBart1->setEnergy(3);
            $wellnessBart1->setThoughts(4);
            $wellnessBart1->setSleep(2);
            $wellnessBart1->setDate(date("Y-m-d"));
            $manager->persist($wellnessBart1);

            $wellnessBart2 = new Wellness();
            $wellnessBart2->setRespondent($respondent3);
            $wellnessBart2->setMood(4);
            $wellnessBart2->setEnergy(4);
            $wellnessBart2->setThoughts(4);
            $wellnessBart2->setSleep(4);
            $wellnessBart2->setDate(date("Y-m-d"));
            $manager->persist($wellnessBart2);

            #endregion

            #region Lisa - Respondent
            $user4 = new User();
            $user4->setUserId('lisaSimpsonID');
            $user4->setFirstName('Lisa');
            $user4->setLastName('Simpson');
            $user4->setEmail('LisaSimpson@gmail.com');
            $user4->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user4->setDate(new \DateTime('1969-11-28'));
            $user4->setCountry('CA');
            $user4->setCity('Springfield');
            $user4->setStatus('active');
            $user4->setIsActive(true);
            $manager->persist($user4);

            //Make member a respondent
            $respondent4 = new Respondent();
            $respondent4->setUser($user4);
            $respondent4->setAtRisk(true);
            $respondent4->setMentalState("Hungry");
            $respondent4->setStressLevel(4);
            $respondent4->setEmergencyContact("Bob");
            $respondent4->setDoctor("Travis");
            $manager->persist($respondent4);

            //Give member 2 wellness records
            $wellnessLisa1 = new Wellness();
            $wellnessLisa1->setRespondent($respondent4);
            $wellnessLisa1->setMood(2);
            $wellnessLisa1->setEnergy(3);
            $wellnessLisa1->setThoughts(4);
            $wellnessLisa1->setSleep(2);
            $wellnessLisa1->setDate(date("Y-m-d"));
            $manager->persist($wellnessLisa1);

            $wellnessLisa2 = new Wellness();
            $wellnessLisa2->setRespondent($respondent4);
            $wellnessLisa2->setMood(4);
            $wellnessLisa2->setEnergy(4);
            $wellnessLisa2->setThoughts(4);
            $wellnessLisa2->setSleep(4);
            $wellnessLisa2->setDate(date("Y-m-d"));
            $manager->persist($wellnessLisa2);

            #endregion

            #region Maggy - Respondent
            $user5 = new User();
            $user5->setUserId('maggySimpsonID');
            $user5->setFirstName('Maggy');
            $user5->setLastName('Simpson');
            $user5->setEmail('MaggySimpson@gmail.com');
            $user5->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user5->setDate(new \DateTime('1969-11-28'));
            $user5->setCountry('CA');
            $user5->setCity('Springfield');
            $user5->setStatus('active');
            $user5->setIsActive(true);
            $manager->persist($user5);

            //Make member a respondent
            $respondent5 = new Respondent();
            $respondent5->setUser($user5);
            $respondent5->setAtRisk(true);
            $respondent5->setMentalState("Hungry");
            $respondent5->setStressLevel(4);
            $respondent5->setEmergencyContact("Bob");
            $respondent5->setDoctor("Travis");
            $manager->persist($respondent5);

            //Give member 2 wellness records
            $wellnessMaggy1 = new Wellness();
            $wellnessMaggy1->setRespondent($respondent5);
            $wellnessMaggy1->setMood(2);
            $wellnessMaggy1->setEnergy(3);
            $wellnessMaggy1->setThoughts(4);
            $wellnessMaggy1->setSleep(2);
            $wellnessMaggy1->setDate(date("Y-m-d"));
            $manager->persist($wellnessMaggy1);

            $wellnessMaggy2 = new Wellness();
            $wellnessMaggy2->setRespondent($respondent5);
            $wellnessMaggy2->setMood(4);
            $wellnessMaggy2->setEnergy(4);
            $wellnessMaggy2->setThoughts(4);
            $wellnessMaggy2->setSleep(4);
            $wellnessMaggy2->setDate(date("Y-m-d"));
            $manager->persist($wellnessMaggy2);

            #endregion

            #region Hibert - Wellness Professional
            $user6 = new User();
            $user6->setUserId('hibbertSimpsonID');
            $user6->setFirstName('Hibbert');
            $user6->setLastName('Simpson');
            $user6->setEmail('HibbertSimpson@gmail.com');
            $user6->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
            $user6->setDate(new \DateTime('1969-11-28'));
            $user6->setCountry('CA');
            $user6->setCity('Springfield');
            $user6->setStatus('active');
            $user6->setIsActive(true);
            $manager->persist($user6);

            //Make user a wellness professional
            $wp1 = new WellnessProfessional();
            $wp1->setUser($user6);
            $wp1->setPracticeName("Black Widow Orphanage");
            $wp1->setContactNumber('3061234567');
            $wp1->setContactEmail("HibbertSimpson@gmail.com");
            $manager->persist($wp1);

            #endregion

            #region Many User Loop

            for($i = 0; $i < 50; $i++)
            {
                $userLoop = new User();
                $userLoop->setUserId('bort'.$i.'SampsonID');
                $userLoop->setFirstName('bort' . $i);
                $userLoop->setLastName('Sampson');
                $userLoop->setEmail('BortSampson'.$i.'@gmail.com');
                $userLoop->setPassword('$2y$13$Ik7OBzdr00bx9xJr8xs4hepks9OjGRL3ZvQj9HTY6xLzlz7ZWQz/u');
                $userLoop->setDate(new \DateTime('1969-11-28'));
                $userLoop->setCountry('CA');
                $userLoop->setCity('Springfield');
                $userLoop->setStatus('active');
                $userLoop->setIsActive(true);
                $manager->persist($userLoop);

                //Make member a respondent
                $respondentLoop = new Respondent();
                $respondentLoop->setUser($userLoop);
                $respondentLoop->setAtRisk(true);
                $respondentLoop->setMentalState("Hungry");
                $respondentLoop->setStressLevel(4);
                $respondentLoop->setEmergencyContact("Bob");
                $respondentLoop->setDoctor("Travis");
                $manager->persist($respondentLoop);

                //Give member 2 wellness records
                $wellnessLoop1 = new Wellness();
                $wellnessLoop1->setRespondent($respondentLoop);
                $wellnessLoop1->setMood(2);
                $wellnessLoop1->setEnergy(3);
                $wellnessLoop1->setThoughts(4);
                $wellnessLoop1->setSleep(2);
                $wellnessLoop1->setDate(date("Y-m-d"));
                $manager->persist($wellnessLoop1);

                $wellnessLoop2 = new Wellness();
                $wellnessLoop2->setRespondent($respondentLoop);
                $wellnessLoop2->setMood(4);
                $wellnessLoop2->setEnergy(4);
                $wellnessLoop2->setThoughts(4);
                $wellnessLoop2->setSleep(4);
                $wellnessLoop2->setDate(date("Y-m-d"));
                $manager->persist($wellnessLoop2);
            }

            #endregion

            //Commit to the database
            $manager->flush();
        }
    }

}